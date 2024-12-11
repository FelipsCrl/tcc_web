<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\VoluntarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::post('/loginApi', [AuthApiController::class, 'login']);
Route::get('/listaHabilidade', [VoluntarioController::class, 'listagemHabilidade']);
Route::post('/cadastro', [VoluntarioController::class, 'cadastro']);
Route::post('/esqueceu', [PasswordResetLinkController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/doar', [DoacaoController::class, 'realizaDoacao']);
    Route::get('/listaDoacao', [DoacaoController::class, 'listagemDoacao']);
    Route::get('/listaEvento', [EventoController::class, 'listagemEvento']);
    Route::post('/inscrever', [EventoController::class, 'inscreveEvento']);
    Route::get('/listaInstituicao', [InstituicaoController::class, 'listagemInstituicao']);
    Route::post('/voluntariar', [InstituicaoController::class, 'inscreveInstituicao']);
    Route::post('/doarAgora', [InstituicaoController::class, 'doarAgora']);
    Route::get('/dadosVoluntario', [VoluntarioController::class, 'dadosVoluntario']);
    Route::get('/listaSolicitacao', [SolicitacaoController::class, 'listagemSolicitacao']);
    Route::post('/atualizaHabilidades', [VoluntarioController::class, 'atualizarHabilidades']);
    Route::post('/atualizaCredenciais', [VoluntarioController::class, 'atualizarCredenciais']);
    Route::post('/atualizaContato', [VoluntarioController::class, 'atualizarContato']);
    Route::post('/atualizaEndereco', [VoluntarioController::class, 'atualizarEndereco']);
    Route::post('/atualizaSenha', [VoluntarioController::class, 'atualizarSenha']);
    Route::get('/logoutApi', [AuthApiController::class, 'logout']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
