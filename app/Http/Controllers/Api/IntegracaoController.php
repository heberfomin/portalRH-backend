<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Sincronismo de funcionarios Datasul (CD0401, emitente grupo 50) -> dbPessoal.tbFuncionarios.
 *
 * Quem chama: a API de funcionarios do Datasul (PASOE, esp/api/v1/funcionarios).
 * Header API-KEY = INTEGRATION_KEY do .env (mesma chave da API abastecimento).
 * Corpo: {...} | {"funcionarios":[...]} | [...]
 *   codEmitente, cpf, nome, nomeAbrev, ativo (bool), dataAdmissao (aaaa-mm-dd),
 *   dataInativacao (aaaa-mm-dd | null)
 * Regras:
 *   - chave codFuncionario (= codIDERP = cod-emitente); CPF em outro codigo = conflito.
 *   - inativo inexistente = ignorado; nunca apaga.
 *   - ativo -> indAtivo 1 e datDemissao NULL; inativo -> indAtivo 0 e datDemissao =
 *     dataInativacao (ou mantem a atual).
 *   - datAdmissao so na inclusao (data-implant do emitente nem sempre e a admissao).
 *   - nao mexe em codRH, desIP, latitude/longitude (sao do portal).
 */
class IntegracaoController extends Controller
{
    public function postFuncionarios(Request $request)
    {
        $key = $request->header('API-KEY');
        if (!$key || $key != env('INTEGRATION_KEY')) {
            return response()->json('ACCESS_DENIED', 403);
        }

        $payload = $request->all();
        if (isset($payload['funcionarios']) && is_array($payload['funcionarios'])) {
            $itens = $payload['funcionarios'];
        } elseif ($payload !== [] && array_keys($payload) === range(0, count($payload) - 1)) {
            $itens = $payload;
        } else {
            $itens = [$payload];
        }

        $resultados = [];
        foreach ($itens as $item) {
            $resultados[] = $this->upsert(is_array($item) ? $item : []);
        }
        return response()->json(['resultados' => $resultados], 200);
    }

    private function data($v)
    {
        if (empty($v)) {
            return null;
        }
        $v = substr(trim((string) $v), 0, 10);
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : null;
    }

    private function upsert(array $f)
    {
        $tab = 'tbFuncionarios';
        $cod = isset($f['codEmitente']) ? trim((string) $f['codEmitente']) : '';
        $res = ['codEmitente' => $cod, 'status' => 'erro', 'mensagem' => ''];
        try {
            if ($cod === '' || !ctype_digit($cod)) {
                $res['mensagem'] = 'codEmitente invalido';
                return $res;
            }
            $cpf = preg_replace('/\D/', '', isset($f['cpf']) ? (string) $f['cpf'] : '');
            $ativo = !empty($f['ativo']);

            $atual = DB::table($tab)->where('codFuncionario', (int) $cod)->first();
            if (!$atual && $cpf !== '') {
                $outro = DB::table($tab)->where('codIDFederal', $cpf)->where('codFuncionario', '<>', (int) $cod)
                    ->where('indAtivo', 1)->first();
                if ($outro) {
                    $res['status'] = 'conflito';
                    $res['mensagem'] = 'CPF ja cadastrado (ativo) no codigo '.$outro->codFuncionario;
                    Log::warning('sync funcionario: conflito', $res);
                    return $res;
                }
            }
            if (!$atual && !$ativo) {
                $res['status'] = 'ignorado';
                $res['mensagem'] = 'inativo e inexistente';
                return $res;
            }

            $dados = [
                'codIDERP' => (int) $cod,
                'indAtivo' => $ativo ? 1 : 0,
            ];
            if (!empty($f['nome'])) {
                $dados['desFuncionario'] = mb_substr(trim($f['nome']), 0, 100);
            }
            if (!empty($f['nomeAbrev'])) {
                $dados['desAbreviado'] = mb_substr(trim($f['nomeAbrev']), 0, 12);
            }
            if ($cpf !== '') {
                $dados['codIDFederal'] = $cpf;
            }
            // data-implant do emitente nem sempre e a admissao real: so usa na inclusao
            $adm = $this->data(isset($f['dataAdmissao']) ? $f['dataAdmissao'] : null);
            if ($adm && !$atual) {
                $dados['datAdmissao'] = $adm;
            }
            if ($ativo) {
                $dados['datDemissao'] = null;
            } else {
                $dem = $this->data(isset($f['dataInativacao']) ? $f['dataInativacao'] : null);
                if ($dem) {
                    $dados['datDemissao'] = $dem;
                }
            }

            if (!$atual) {
                $dados['codFuncionario'] = (int) $cod;
                DB::table($tab)->insert($dados);
                $res['status'] = 'incluido';
                Log::info('sync funcionario incluido', $dados);
                return $res;
            }

            $mudou = [];
            foreach ($dados as $col => $val) {
                $velho = $atual->{$col};
                if (in_array($col, ['datAdmissao', 'datDemissao']) && $velho) {
                    $velho = substr($velho, 0, 10);
                }
                if ($velho === null && $val === null) {
                    continue;
                }
                if ((string) $velho !== (string) $val) {
                    $mudou[$col] = $val;
                }
            }
            if (!$mudou) {
                $res['status'] = 'sem_alteracao';
                return $res;
            }
            DB::table($tab)->where('codFuncionario', (int) $cod)->update($mudou);
            $res['status'] = 'atualizado';
            Log::info('sync funcionario atualizado', ['cod' => $cod, 'depois' => $mudou]);
            return $res;
        } catch (\Exception $e) {
            $res['mensagem'] = mb_substr($e->getMessage(), 0, 300);
            Log::error('sync funcionario: erro', $res);
            return $res;
        }
    }
}
