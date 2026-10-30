<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use \DB;
use App\User;
use App\tbFuncionarios;
use App\tbEnderecos;
use App\tbTelefones;
use App\tbPosts;
use App\tbEmails;
use App\vwDocPeriodo;
use App\vwFuncionariosUsers;
use \Datetime;

class getData extends Controller
{
    public function getFuncionarios(Request $request)
    {   
        $jsonData = [];
        $funcionarios = tbFuncionarios::where('indAtivo','=',1)->get();
        foreach ($funcionarios as $rec) {
            $endereco = tbEnderecos::where('codFuncionario',$rec->codFuncionario)->get();
            $address = [];
            foreach($endereco as $addr) {
            $address[] = [ 'enderecoAk' => $addr->enderecoAk,
                         'codFuncionario'  => $addr->codFuncionario,
                         'desLogradouro'   => $addr->desLogradouro,
                         'desNumero'       => $addr->desNumero, 
                         'desComplemento'  => $addr->desComplemento,
                         'desCEP'          => $addr->desCEP,
                         'desBairro'       => $addr->desBairro,     
                         'desCidade'       => $addr->desCidade,     
                         'desEstado'       => $addr->desEstado,     
                         'desPais'         => $addr->desPais,     
                         'indPrincipal'    => $addr->indPrincipal,  
                         'indTipo'         => $addr->indTipo
            ];
            }
            $telefones = tbTelefones::where('codFuncionario',$rec->codFuncionario)->get();
            $phone = [];
            foreach ($telefones as $tel) {
                $phone[] = [ 'telefoneAk'      => $tel->telefoneAk,    
                             'codFuncionario'  => $tel->codFuncionario,
                             'desDDD'          => $tel->desDDD,        
                             'desTelefone'     => $tel->desTelefone,   
                             'desTipo'         => $tel->desTipo 
                            ];
            }
            $emails = tbEmails::where('codFuncionario',$rec->codFuncionario)->get();
            $mail = [];
            foreach ($emails as $e_mail) {
                $mail[] = [ 'emailAk'        => $e_mail->emailAk,
                            'codFuncionario' => $e_mail->codFuncionario,
                            'desEmail'       => $e_mail->desEmail,
                            'indTipo'        => $e_mail->indTipo
                          ];
            }
            $jsonData[] = [ 'funcionario' => [ 'funcionarioAk' => $rec->funcionarioAk,
                                                'codFuncionario' => $rec->codFuncionario,
                                                'codRH' => $rec->codRH,
                                                'codIDFederal' => $rec->codIDFederal,
                                                'codIDERP' => $rec->codIDERP,
                                                'desFuncionario' => $rec->desFuncionario,
                                                'desAbreviado' => $rec->desAvreviado,
                                                'datAdmissao' => $rec->datAdmissao,
                                                'datDemissao' => $rec->datDemissao,
                                                'indAtivo' => $rec->indAtivo  ], 
                            'endereco' => $address,
                            'telefone' => $phone,
                            'email' => $mail ];
        }
        //dd($jsonData);
        //$data = \json_encode($jsonData);
        
        return response()->json(compact('jsonData'));
    }
    public function getFuncionario(Request $request)
    {
        if (!$request->only('codFuncionario') == null ) {
            $codFuncionario = implode('',$request->only('codFuncionario'));
        }
        if (!$request->only('funcionarioAk') == null) {
            $funcionarioAk  = implode('',$request->only('funcionarioAk'));
        }
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        if (isset($funcionarioAk)) {
            if ($funcionario = vwFuncionariosUsers::select()->where('funcionarioAk',$funcionarioAk)->get()) {
                return response()->json(compact('funcionario'));
            }
        }
        elseif (isset($codFuncionario)) {
            if ($codFuncionario == 0) {
                if ($funcionario = vwFuncionariosUsers::all()) {
                    return response()->json(compact('funcionario'));
                } 
            }
            if ($codFuncionario != '0') {    
                if ($funcionario = vwFuncionariosUsers::select()->where('codFuncionario',$codFuncionario)->get()) {
                    return response()->json(compact('funcionario'));
                }
            }
        }
        return $response->setStatusCode($status, $msg);  
    }     

    public function getEndereco(Request $request)
    {
        //dd($request);
        $codFuncionario = $request->only('codFuncionario');
        $enderecoAk = $request->only('enderecoAk');
        //dd($codFuncionario);
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                if ($endereco = tbEnderecos::select()->where('codFuncionario',$codFuncionario)->get()) {
                    return response()->json(compact('endereco'));
                }
            }
        }
        if ($enderecoAk) {
            if ($endereco = tbEnderecos::select()->where('enderecoAk',$enderecoAk)->get()) {
                return response()->json(compact('endereco'));
            }
        }
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        return $response->setStatusCode($status, $msg);
    }
    public function getTelefone(Request $request)
    {
        //dd($request);
        $codFuncionario = $request->only('codFuncionario');
        $telefoneAk = $request->only('telefoneAk');
        //dd($codFuncionario);
        if ($codFuncionario) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $telefone = [];
                if ($bunch = tbTelefones::select()->where('codFuncionario',$codFuncionario)->get()) {
                    foreach($bunch as $data) {
                        $telefone[] = [
                            'telefoneAk'      => $data->telefoneAk,
                            'codFuncionario'  => $data->codFuncionario,
                            'desDDD'          => $data->desDDD,
                            'desTelefone'     => $data->desTelefone,
                            'desTipo'         => $data->desTipo,
                        ]; 
                    }
                }
                return response()->json(compact('telefone'));
            }
        }
        if ($telefoneAk) {
            if ($telefone = tbTelefones::select()->where('telefoneAk',$telefoneAk)->get()) {
                return response()->json(compact('telefone'));
            }
        }
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        return $response->setStatusCode($status, $msg);
    }
    public function getEmail(Request $request)
    {
        //dd($request);
        $codFuncionario = $request->only('codFuncionario');
        $emailAk = $request->only('emailAk');
        //dd($codFuncionario);
        if (!empty($codFuncionario)) {
            if ($funcionario = tbFuncionarios::select()->where('codFuncionario',$codFuncionario)->get()) {
                $mail = [];
                if ($bunch = tbEmails::select()->where('codFuncionario',$codFuncionario)->get()) {
                    foreach($bunch as $data) {
                        $email[] = [
                            'emailAk' => $data->emailAk,
                            'codFuncionario' => $data->codFuncionario,
                            'desEmail' => $data->desEmail, 
                            'indTipo' => $data->indTipo
                        ]; 
                    }
                }
                return response()->json(compact('email'));
            }
        }
        if ($emailAk) {
            if ($email = tbEmails::select()->where('emailAk',$emailAk)->get()) {
                return response()->json(compact('email'));
            }
        }
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        return $response->setStatusCode($status, $msg);
    }
    public function getFuncPeriodo(Request $request)
    {
        $codFuncionario = $request->only('codFuncionario');
        $codTipo = $request->only('codTipo');
        if ( $codFuncionario && $codTipo ) {
            if ( implode('',$codTipo) === '0' ) {
                $op = '<>';
            } else {
                $op = '=';
            }
            $documentos = DB::table('tbDocumentos')
                ->where('tbDocumentos.codFuncionario',$codFuncionario)
                ->where('codTipo',$op,$codTipo)
                ->join('tbFuncionarios', 'tbDocumentos.codFuncionario', '=', 'tbFuncionarios.codFuncionario')
                ->select('tbFuncionarios.funcionarioAk', 'tbDocumentos.*')
                ->orderBy('datDocumento','desc')
                ->get();
                return response()->json(compact('documentos'));
        }
        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        return $response->setStatusCode($status, $msg);
    }
    public function getDocumentos(Request $request)
    {
        $input = (object)$request->all();
        //dd($input);
        $codFuncionario = $input->codFuncionario;
        $data = $input->data;

        $response  = new Response();
        $status = 400;
        $msg = 'Bad Request';
        if ($codFuncionario && $data) {
            $date = new Datetime($data);
            $dataDoc = $date->format('d/m/Y');
            $documentos = vwDocPeriodo::select()->where('codFuncionario',$codFuncionario)
            ->where('datDocumento',$dataDoc)
            ->get();
            if ($documentos->count() == 0) {
                $status = 404;
                $msg = 'Not found';
            } else {
                return response()->json(compact('documentos'));            
            }
        }
        return $response->setStatusCode($status, $msg);
    }
    public function getPosts(Request $request)
    {
        $input = (object)$request->all();
        $func  = tbFuncionarios::where('codFuncionario',$input->codFuncionario)->first();
        if ( $func ) {
            $posts = tbPosts::where('codFuncionario',$input->codFuncionario)
            ->where('indStatus',0)
            ->get();
            if ( $posts ) {
                return Response()->json($posts,200);
            }
        }
        return Response()->json('BAD_REQUEST',400);
    }
}
