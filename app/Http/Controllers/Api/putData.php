<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use DB;
use App\tbFuncionarios;
use App\tbEnderecos;
use App\tbTelefones;
use App\tbEmails;
use App\tbDocumentos;
use \Datetime;

class putData extends Controller
{
    public function putEndereco(Request $request)
    {
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
        $response       = new Response();
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $endereco = tbEnderecos::select()
                ->where('codFuncionario',$codFuncionario)
                ->where('desLogradouro',$desLogradouro)
                ->where('desNumero',$desNumero)->get();
                //dd($funcionario->count(),$endereco->count());
                if ($endereco->count() > 0) {
                    $status = 409;
                    $msg = 'Unalthorized - Already Exists';
                    return $response->setStatusCode($status, $msg);
                }
            } else {
                $status = 400;
                $msg = 'Bad Request';
                return $response->setStatusCode($status, $msg);
            }
        }  else {
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }

        if ($funcionario && $endereco->count() == 0) {
            //dd($codFuncionario,$desLogradouro);
            $address = new tbEnderecos();
            $address->codFuncionario = $codFuncionario;
            $address->desLogradouro  = $desLogradouro;
            $address->desNumero      = $desNumero;     
            $address->desComplemento = $desComplemento;
            $address->desCEP         = $desCEP;        
            $address->desBairro      = $desBairro;     
            $address->desCidade      = $desCidade;     
            $address->desEstado      = $desEstado;     
            $address->desPais        = $desPais;       
            $address->indPrincipal   = $indPrincipal;  
            $address->indTipo        = $indTipo;       
            $address->save();
            $status = 200;
            $msg = 'Ok';
            return $response->setStatusCode($status, $msg);
        }

    }
    public function putTelefone(Request $request)
    {
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $desDDD         = implode('',$request->only('desDDD'));        
        $desTelefone    = implode('',$request->only('desTelefone'));   
        $desTipo        = implode('',$request->only('desTipo')); 
        $response       = new Response();
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $telefone = tbTelefones::select()
                ->where('codFuncionario',$codFuncionario)
                ->where('desTelefone',$desTelefone)->get();
                if ($telefone->count() > 0) {
                    $status = 409;
                    $msg = 'Unalthorized - Already Exists';
                    return $response->setStatusCode($status, $msg);
                }
            } else {
                $status = 400;
                $msg = 'Bad Request - key does not exists';
                return $response->setStatusCode($status, $msg);
            }
        }  else {
            $status = 400;
            $msg = 'Bad Request - Key did not supplyed';
            return $response->setStatusCode($status, $msg);
        }

        if ($funcionario && $telefone->count() == 0) {
            $phone = new tbTelefones();
            $phone->codFuncionario = $codFuncionario;
            $phone->desDDD         = $desDDD;        
            $phone->desTelefone    = $desTelefone;   
            $phone->desTipo        = $desTipo;             
            $phone->save();
            $status = 200;
            $msg = 'Ok';
            return $response->setStatusCode($status, $msg);
        }

    }
    public function putEmail(Request $request)
    {
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $desEmail       = implode('',$request->only('desEmail'));   
        $indTipo        = implode('',$request->only('indTipo')); 
        $response       = new Response();
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $Email = tbEmails::select()
                ->where('codFuncionario',$codFuncionario)
                ->where('desEmail',$desEmail)->get();
                if ($Email->count() > 0) {
                    $status = 409;
                    $msg = 'Unalthorized - Already Exists';
                    return $response->setStatusCode($status, $msg);
                }
            } else {
                $status = 400;
                $msg = 'Bad Request - key does not exists';
                return $response->setStatusCode($status, $msg);
            }
        }  else {
            $status = 400;
            $msg = 'Bad Request - Key did not supplyed';
            return $response->setStatusCode($status, $msg);
        }

        if ($funcionario && $Email->count() == 0) {
            $mail = new tbEmails();
            $mail->codFuncionario = $codFuncionario;
            $mail->desEmail       = $desEmail;   
            $mail->indTipo        = $indTipo;             
            $mail->save();
            $status = 200;
            $msg = 'Ok';
            return $response->setStatusCode($status, $msg);
        }
    }
    public function putPDF(Request $request)
    {
        $codFuncionario = implode('',$request->only('codFuncionario'));
        $datDocumento   = implode('',$request->only('datDocumento'));
        $codTipo        = implode('',$request->only('codTipo'));
        $desArquivo     = implode('',$request->only('desArquivo'));
        $date = new Datetime($datDocumento);
        $date = $date->format('d/m/Y');
        if (\is_numeric(substr($datDocumento,0,4))) {
            $periodo = intval(substr($datDocumento,0,4)) * 100 + intval(substr($datDocumento,5,2));
        } else {
            if ( substr($datDocumento,2,1) == '-' || substr($datDocumento,2,1) == '/'  ) {
                $periodo = intval(substr($datDocumento,6,4)) * 100 + intval(substr($datDocumento,3,2));
            } elseif ( substr($datDocumento,4,1) == '-' || substr($datDocumento,4,1) == '/' ) {
                $periodo = intval(substr($datDocumento,6,4)) * 100 + intval(substr($datDocumento,3,2));
            } else {
                $periodo = intval($date->format('Y')) * 100 + intval($date->format('m'));
            }
        }
        $response        = new Response();
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $documento = tbDocumentos::select()
                ->where('codFuncionario',$codFuncionario)
                ->where('codTipo',$codTipo)
                ->where('datDocumento',$date)->get();
                if ($documento->count() > 0) {
                    $status = 409;
                    $msg = 'Unalthorized - Already Exists';
                    return $response->setStatusCode($status, $msg);
                }
            } else {
                $status = 400;
                $msg = 'Bad Request - key does not exists';
                return $response->setStatusCode($status, $msg);
            }
        }  else {
            $status = 400;
            $msg = 'Bad Request - Key did not supplyed';
            return $response->setStatusCode($status, $msg);
        }
        if ($funcionario && $documento->count() == 0) {
            $doctos = new tbDocumentos();
            $doctos->codFuncionario = $codFuncionario;
            $doctos->datDocumento   = $date;
            $doctos->numPeriodo     = $periodo;
            $doctos->codTipo        = $codTipo;
            $doctos->desArquivo     = $desArquivo;
            $doctos->save();
        }
    }
}
