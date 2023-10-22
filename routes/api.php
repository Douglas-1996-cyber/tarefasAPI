<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\MensagemCadastro;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::apiResource('tarefa','App\Http\Controllers\TarefaController');
    Route::post('me','App\Http\Controllers\AuthController@me');
    Route::post('logout','App\Http\Controllers\AuthController@logout');

    Route::get('exportacao','App\Http\Controllers\TarefaController@exportacao');
    Route::patch('concluir/{id}','App\Http\Controllers\TarefaController@concluir');
    Route::patch('senha','App\Http\Controllers\AuthController@alterarSenha');
    Route::get('exportar','App\Http\Controllers\TarefaController@exportar');
});

Route::post('login','App\Http\Controllers\AuthController@login');
Route::post('register','App\Http\Controllers\Auth\RegisterController@register');
Route::post('refresh','App\Http\Controllers\AuthController@refresh');
Route::get('enviaremail','App\Http\Controllers\AuthController@enviarEmail');






