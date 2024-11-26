<?php

use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\VoluntarioController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/instituicao/store', [InstituicaoController::class, 'store'])->name('instituicao.store');
Route::get('/instituicao/create', [InstituicaoController::class, 'create'])->name('instituicao.create');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/inicio', [HomeController::class, 'index'])->name('home');

    Route::resource('doacao', DoacaoController::class);
    Route::resource('evento', EventoController::class);
    Route::resource('instituicao', InstituicaoController::class)->except(['create', 'store']); // Protege todos os métodos, exceto o create
    Route::resource('solicitacao', SolicitacaoController::class);
    Route::resource('voluntario', VoluntarioController::class);
});
