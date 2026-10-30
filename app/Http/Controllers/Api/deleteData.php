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

class deleteData extends Controller
{
    public function deleteEndereco(Request $request)
    {
        $enderecoAk = implode('',$request->only('enderecoAk'));
        $response = new Response();
        if (!$enderecoAk) {
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if (!($endereco = tbEnderecos::findOrFail($enderecoAk))) {
            $status = 404;
            $msg = 'Key not found';
            return $response->setStatusCode($status, $msg);
        }
        $endereco->delete();
        $status = 200;
        $msg = 'Ok';
        return $response->setStatusCode($status, $msg);
    }
    public function deleteTelefone(Request $request)
    {
        $telefoneAk = implode('',$request->only('telefoneAk'));
        $response = new Response();
        if (empty($telefoneAk)) {
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if (!($telefone = tbTelefones::findOrFail($telefoneAk))) {
            $status = 404;
            $msg = 'Key not found';
            return $response->setStatusCode($status, $msg);
        } else {
            $telefone->delete();
            $status = 200;
            $msg = 'Ok';
            return $response->setStatusCode($status, $msg);
        }
    }
    public function deleteEmail(Request $request)
    {
        $emailAk = implode('',$request->only('emailAk'));
        $response = new Response();
        if (!$emailAk) {
            $status = 400;
            $msg = 'Bad Request';
            return $response->setStatusCode($status, $msg);
        }
        if (!($email = tbEmails::findOrFail($emailAk))) {
            $status = 404;
            $msg = 'Unauthorized - Key not found';
            return $response->setStatusCode($status, $msg);
        }
        $email->delete();
        $status = 200;
        $msg = 'Ok';
        return $response->setStatusCode($status, $msg);
        # code...
    }
    public function deletePDF(Request $request)
    {
        $documentoAk = implode('',$request->only('documentoAk'));
        $response = new Response();
        $status = 400;
        $msg = 'Bad Request';
        if ($documentoAk) {
            if ($documento = tbDocumentos::findOrFail($documentoAk)) {
                $documento->delete();
                $status = 200;
                $msg = 'Ok';
            }
        }
        return $response->setStatusCode($status, $msg);        
    }
}
