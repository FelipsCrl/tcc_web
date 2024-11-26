<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InstituicaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/loginApi', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/listaDoacao', [DoacaoController::class, 'listagemDoacao']);
    Route::post('/doar', [DoacaoController::class, 'realizaDoacao']);
    Route::get('/listaEvento', [EventoController::class, 'listagemEvento']);
    Route::post('/inscrever', [EventoController::class, 'inscreveEvento']);
    Route::get('/listaInstituicao', [InstituicaoController::class, 'listagemInstituicao']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
