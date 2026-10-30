<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use \DB;
use App\tbFuncionarios;
use App\tbEnderecos;
use App\tbTelefones;
use App\tbEmails;
use App\tbDocumentos;
use App\tbPosts;
use App\User;
use \Datetime;

class postData extends Controller
{
    public function postFuncionario(Request $request)
    {
        $funcionarioAk   = $request->only('funcionarioAk');
        $codFuncionario  = $request->only('codFuncionario');
        $codRH           = $request->only('codRH');
        $codIDFederal    = $request->only('codIDFederal');
        $codIDERP        = $request->only('codIDERP');
        $desFuncionario  = $request->only('desFuncionario');
        $desAbreviado    = $request->only('desAbreviado');
        $datAdmissao     = $request->only('datAdmissao');
        $datDemissao     = $request->only('datDemissao');
        $indAtivo        = $request->only('indAtivo');
        if ($funcionarioAk) {
            $funcionario = tbFuncionarios::select()->where('funcionarioAk',$funcionarioAk)->get();
        } elseif ($codFuncionario) {
            $funcionario = tbFuncionarios::select()->where('codFuncionario',$funcionarioAk)->get();
        } else {
            $response  = new Response();
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if ($funcionario) {
            //dd($codRH,$codIDFederal,$codIDERP,$desFuncionario,$desAbreviado,$datAdmissao,$datDemissao,$indAtivo);   
            $icodRH          = implode('',$codRH) != '' ? implode('',$codRH) : 'NULL';          
            $ccodIDFederal   = implode('',$codIDFederal) != '' ? implode('',$codIDFederal) : 'NULL';   
            $icodIDERP       = implode('',$codIDERP) != '' ? implode('',$codIDERP) : 'NULL';       
            $cdesFuncionario = implode('',$desFuncionario)  != '' ? implode('',$desFuncionario) : 'NULL'; 
            $cdesAbreviado   = implode('',$desAbreviado)  != '' ? implode('',$desAbreviado) : 'NULL';   
            $ddatAdmissao    = implode('',$datAdmissao) != '' ? implode('',$datAdmissao) : 'NULL'; 
            if ($ddatAdmissao != 'NULL') {
                $d = new DateTime($ddatAdmissao);
                $ddatAdmissao    = $d->format('d/m/Y');
            }
            $ddatDemissao    = implode('',$datDemissao) != '' ? implode('',$datDemissao) : 'NULL'; 
            if ($ddatDemissao != 'NULL') {
                $d = new DateTime($ddatDemissao);
                $ddatDemissao    = $d->format('d/m/Y');
            }
            $iindAtivo       = implode('',$indAtivo) != '' ? implode('',$indAtivo) : 1;    
            foreach ($funcionario as $data) {
                    tbFuncionarios::where('funcionarioAk', $data->funcionarioAk)
                    ->update([
                                'codRH'           => $icodRH,         
                                'codIDFederal'    => $ccodIDFederal,  
                                'codIDERP'        => $icodIDERP,      
                                'desFuncionario'  => $cdesFuncionario,
                                'desAbreviado'    => $cdesAbreviado,  
                                'datAdmissao'     => $ddatAdmissao == 'NULL' ? NULL : $ddatAdmissao,   
                                'datDemissao'     => $ddatDemissao == 'NULL' ? NULL : $ddatDemissao,   
                                'indAtivo'        => $iindAtivo      
                ]);
                    $response  = new Response();
                    $status = 200;
                    $msg = 'Ok';
                    return $response->setStatusCode($status, $msg);
            }
        }
    }
    public function postEndereco(Request $request)
    {
        $enderecoAk     = implode('',$request->only('enderecoAk'));
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $desLogradouro  = implode('',$request->only('desLogradouro'));
        $desNumero      = implode('',$request->only('desNumero'));
        $desComplemento = implode('',$request->only('desComplemento'));
        $desCEP         = implode('',$request->only('desCEP'));
        $desBairro      = implode('',$request->only('desBairro'));
        $desCidade      = implode('',$request->only('desCidade'));
        $desEstado      = implode('',$request->only('desEstado'));
        $desPais        = implode('',$request->only('desPais'));
        $indPrincipal   = implode('',$request->only('indPrincipal'));
        $indTipo        = implode('',$request->only('indTipo'));
        if ($enderecoAk) {
            $endereco = tbEnderecos::select()->where('enderecoAk',$enderecoAk)->get();
        } else {
            $response  = new Response();
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if ($endereco) {
            foreach ($endereco as $data) {
               tbEnderecos::where('enderecoAk', $data->enderecoAk)
                    ->update([
                        'desLogradouro'  => $desLogradouro,
                        'desNumero'      => $desNumero,
                        'desComplemento' => $desComplemento,
                        'desCEP'         => $desCEP,
                        'desBairro'      => $desBairro,
                        'desCidade'      => $desCidade,
                        'desEstado'      => $desEstado,
                        'desPais'        => $desPais,
                        'indPrincipal'   => $indPrincipal,
                        'indTipo'        => $indTipo
                    ]);
            }
        }
    }
    public function postTelefone(Request $request)
    {
        $telefoneAk     = implode('',$request->only('telefoneAk'));     
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $desDDD         = implode('',$request->only('desDDD'));        
        $desTelefone    = implode('',$request->only('desTelefone'));   
        $desTipo        = implode('',$request->only('desTipo')); 
        if ($telefoneAk) {
            $telefone = tbTelefones::select()->where('TelefoneAk',$telefoneAk)->get();
        } else {
            $response  = new Response();
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if ($telefone) {
            foreach ($telefone as $data) {
                tbTelefones::where('telefoneAk', $data->telefoneAk)
                    ->update([
                        'desDDD'         => $desDDD,        
                        'desTelefone'    => $desTelefone,   
                        'desTipo'        => $desTipo
                    ]);
            }
        }
    }
    public function postEmail(Request $request)
    {
        $emailAk        = implode('',$request->only('emailAk'));       
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $desEmail       = implode('',$request->only('desEmail'));      
        $indTipo        = implode('',$request->only('indTipo')); 
        if ($emailAk) {
            $email = tbEmails::select()->where('emailAk',$emailAk)->get();
        } else {
            $response  = new Response();
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if ($email) {
            foreach ($email as $data) {
               tbEmails::where('emailAk', $data->emailAk)
                    ->update([ 
                        'desEmail'       => $desEmail,
                        'indTipo'        => $indTipo 
                    ]);
            }
        }
    }
    public function postPDF(Request $request)
    {
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $datDocumento   = implode('',$request->only('datDocumento'));
        $codTipo        = implode('',$request->only('codTipo'));
        $desArquivo     = implode('',$request->only('desArquivo'));

        $date = new Datetime($datDocumento);
        $date = $date->format('d/m/Y');
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';

        if ($codFuncionario) {
            $documento = tbDocumentos::select()
            ->where('codFuncionario',$codFuncionario)
            ->where('datDocumento',$date)
            ->where('codTipo',$codTipo)->get();
        } 
        if ($documento->count() > 0) {
            foreach ($documento as $data) {
               tbDocumentos::where('documentoAk', $data->documentoAk)
                    ->update([ 
                        'desArquivo'     => $desArquivo
                    ]);
                }
            $status = 200;
            $msg = 'Ok';
        }
        return $response->setStatusCode($status, $msg);
    }

    public function postResetSenha(Request $request)
    {
        $codFuncionario = $request->only('codFuncionario');
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        if ( !empty($codFuncionario) ) {
            $chave = implode('',$codFuncionario);
            $usuario = User::where('codFuncionario',$chave)
                           ->where('username','<>','Admin')->get();
            if (!$usuario) {
                $status = 404;
                $msg = 'Not Found';
            } else {
                if ( $chave === '0' ) {
                    User::where('username','<>','Admin')
                        ->update(['indSenhaExpirada' => 1]);
                } else {
                    User::where('codFuncionario',$chave)->update(['indSenhaExpirada' => 1]);
                }
                $status = 200;
                $msg = 'Ok';
            }
        }
        return $response->setStatusCode($status, $msg);
    }
    public function postIP(Request $request)
    {
        $input = (object)$request->all();
        $funcionario = tbFuncionarios::where('codFuncionario',$input->codFuncionario)->get();
        if ( !$funcionario->isEmpty() ) {
            $funcionario = tbFuncionarios::where('codFuncionario',$input->codFuncionario)
            ->update([
                'desIP' => $input->desIP,
                'desLatitude' => $input->desLatitude,
                'desLongitude' => $input->desLongitude
            ]);
            return Response()->json('OK',200);
        }
        return Response()->json('BAD_REQUEST',400);
    }
    public function postPosts(Request $request)
    {
        $input   = (object)$request->all();
        $origem  = $input->codOrigem;
        $destino = $input->codFuncionario;
        $texto   = $input->desPost;
        $assunto = $input->desAssunto;
        $data    = $input->datPost;
        $funcDe  = tbFuncionarios::where('codFuncionario',$origem)->first();
        if ( $funcDe ) {
            if ( $destino == 0 || $destino == '0' ) {
                $funcionarios = tbFuncionarios::where('codFuncionario','<>',$origem)->get();
                if ( $funcionarios ) {
                    foreach ( $funcionarios as $dests ) {
                        $post = new tbPosts;
                        $post->codFuncionario = $dests->codFuncionario;
                        $post->codOrigem      = (int)$origem;
                        $post->desAssunto     = $assunto;
                        $post->desPost        = $texto;
                        $date                 = new Datetime($data);
                        $post->datPost        = $date->format('d/m/Y H:i:s');
                        $post->indStatus      = 0;
                        $post->save();
                    }
                    return Response()->json('OK',200);
                }
            } elseif ( strrchr($destino,",") ) {
                $lista = str_replace('"','',$destino); 
                $funcionarios = tbFuncionarios::whereRaw("codFuncionario in (". str_replace('"','',$lista).")")->get();
                if ( $funcionarios ) {
                    foreach ( $funcionarios as $dests ) {
                        $post = new tbPosts;
                        $post->codFuncionario = $dests->codFuncionario;
                        $post->codOrigem      = (int)$origem;
                        $post->desAssunto     = $assunto;
                        $post->desPost        = $texto;
                        $date                 = new Datetime($data);
                        $post->datPost        = $date->format('d/m/Y H:i:s');
                        $post->indStatus      = 0;
                        $post->save();
                    }
                    return Response()->json('OK',200);
                }
            } else {
                $to = tbFuncionarios::where('codFuncionario',$destino)->first();
                if ( $to ) {
                    $post = new tbPosts;
                    $post->codFuncionario = $to->codFuncionario;
                    $post->codOrigem      = (int)$origem;
                    $post->desAssunto     = $assunto;
                    $post->desPost        = $texto;
                    $date                 = new Datetime($data);
                    $post->datPost        = $date->format('d/m/Y H:i:s');
                    $post->indStatus      = 0;
                    $post->save();
                    return Response()->json('OK',200);
                }
            }
        }
        return Response()->json('BAD_REQUEST',400);
    }
    public function setPostRead(Request $request)
    {
        $input = (object)$request->all();
        $post  = tbPosts::where('postsAk',$input->postAk)->first();
        if ( $post ) {
            $post = tbPosts::where('postsAk',$input->postAk)->update(['indStatus' => 1]);
            return Response()->json('Ok',200);
        }
        return Response()->json('BAD_REQUEST',400);
    }

}
