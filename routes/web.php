<?php

use App\Http\Controllers\CadastroController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('web', 'auth')->group(function () {
    Route::get('/desafio-avelar', [Controller::class, 'index'])->name('desafio.avelar.index');
});

Route::get('/desafio-avelar', [CadastroController::class, 'index'])->name('desafio.avelar.index');
Route::get('/', [CadastroController::class, 'index']);

Route::get('/cadastro/listar', [CadastroController::class, 'listar']);
Route::get('/cadastro/estatisticas', [CadastroController::class, 'estatisticas']);
Route::post('/cadastro', [CadastroController::class, 'store']);
Route::get('/cadastro/{id}', [CadastroController::class, 'show']);
Route::put('/cadastro/{id}', [CadastroController::class, 'update']);
Route::delete('/cadastro/{id}', [CadastroController::class, 'destroy']);
