<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;
use App\User;
use Illuminate\Http\Response;
use \DB;
use App\tbFuncionarios;

class AuthController extends Controller
{
    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }
    
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('username', 'password');

        if (! $token = $this->jwtAuth->attempt($credentials)) {
            return response()->json(['error' => 'credenciais_invalidas'], 403);
        }
        return response()->json(compact('token'));
    }

    public function refresh() 
    {
        $token = $this->jwtAuth->getToken();
        $token = $this->jwtAuth->refresh($token);

        return response()->json(compact('token'));
        
    }

    public function logout()
    {
        $token = $this->jwtAuth->getToken();
        $this->jwtAuth->invalidate($token);

        return response()->json(['logout']);
        
    }

    public function me()
    {
        if (! $user = $this->jwtAuth->parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found'], 404);
            }
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }   

    public function postUser(Request $request)
    {
        //$inputData = (object)$request->all();
        $inputData = $request->only('name','username','password','email','perfil','codFuncionario');
        $name      = $inputData['name'];
        $username  = $inputData['username'];
        $password  = $inputData['password'];
        $email     = $inputData['email'];
        $perfil    = $inputData['perfil'];
        $codFuncionario = $inputData['codFuncionario'];
        $response  = new Response();
        $status = 401;
        $msg = 'The User was not created';
        if (empty($name)||empty($username)||empty($password)||empty($email)||empty($perfil)||empty($codFuncionario)) {
            $msg = 'Missing data';
            return $response->setStatusCode($status, $msg);
        }
        if ($func = tbFuncionarios::select('funcionarioAk')->where('codFuncionario',$codFuncionario)->get()){
            $user = User::select('id')->where('username', $username)->first();
            if (!$user) {
                $mail = User::select('email')->where('email', $email)->first();
                if (!$mail ) {
                    $user = new User();
                    $user->name     = $name;
                    $user->username = $username;
                    $user->password = \Hash::make($password);
                    $user->email    = $email;
                    $user->perfil   = $perfil;
                    $user->codFuncionario = $codFuncionario;
                    $user->indSenhaExpirada = 1;
                    $user->save();
                    $status = 201;
                    $msg = 'The User was created successfully';
                }
            } 
        }
        return $response->setStatusCode($status, $msg);
    }

    public function postPassword(Request $request)
    {
        $inputData = $request->only('username','password', 'newUsername');
        $username  = $inputData['username'];
        $newUsername = $inputData['newUsername'];
        
        $password  = $inputData['password'];

        $response  = new Response();      
        $status = 401;
        $msg = 'The User was not updated';
        
        $user = User::select('id')->where('username', $username)->first();
        if ($user) {
            if ( $password ) {
                $user->password = \Hash::make($password);
                $user->indSenhaExpirada = 0;
            }

            if ( $newUsername && $username != $newUsername ) {
                $user->username = $newUsername;
            }

            $user->save();
            $status = 201;
            $msg = 'The User was updated successfully';
        }
        return $response->setStatusCode($status, $msg);
    }


}
