<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/login', 'Api\AuthController@login');
Route::post('auth/refresh','Api\AuthController@refresh');   
Route::post('auth/logout','Api\AuthController@logout');

// Sincronismo de funcionarios Datasul (CD0401 grupo 50) -> tbFuncionarios; header API-KEY = INTEGRATION_KEY
Route::post('postSyncFuncionarios', 'Api\IntegracaoController@postFuncionarios');

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Api\\'], function() {
    
    Route::get('auth/me','AuthController@me'); 
    Route::post('auth/postUser','AuthController@postUser');  
    Route::post('auth/postPassword','AuthController@postPassword'); 
    Route::get('getFuncionarios','getData@getFuncionarios'); 
    Route::get('getFuncionario','getData@getFuncionario'); 
    Route::get('getEndereco','getData@getEndereco'); 
    Route::get('getEmail','getData@getEmail'); 
    Route::get('getTelefone','getData@getTelefone'); 
    Route::get('getFuncPeriodo','getData@getFuncPeriodo'); 
    Route::get('getDocumentos','getData@getDocumentos'); 
    Route::get('getPosts','getData@getPosts'); 
    Route::get('setPostRead','postData@setPostRead'); 
    Route::post('postFuncionario','postData@PostFuncionario'); 
    Route::post('postEndereco','postData@PostEndereco'); 
    Route::post('postTelefone','postData@PostTelefone'); 
    Route::post('postEmail','postData@PostEmail');
    Route::post('postPDF','postData@PostPDF');
    Route::post('postIP','postData@PostIP');
    Route::post('postPosts','postData@postPosts');
    Route::post('postResetSenha','postData@PostResetSenha');
    Route::put('putEndereco','putData@PutEndereco');
    Route::put('putTelefone','putData@PutTelefone');
    Route::put('putEmail','putData@PutEmail');
    Route::put('putPDF','putData@putPDF');
    Route::delete('deleteEndereco','deleteData@deleteEndereco');
    Route::delete('deleteTelefone','deleteData@deleteTelefone');
    Route::delete('deleteEmail','deleteData@deleteEmail');
    Route::delete('deletePDF','deleteData@deletePDF');
    
});


