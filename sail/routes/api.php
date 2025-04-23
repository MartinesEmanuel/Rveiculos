<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\RevisaoController;
use App\Http\Controllers\RelatorioController;

// Rota de teste para verificar se a API está funcionando
Route::get('/teste', function () {
    return response()->json(['message' => 'Rota de teste funcionando!']);
});

// Rotas RESTful para os recursos principais
Route::apiResource('pessoas', PessoaController::class);
Route::apiResource('veiculos', VeiculoController::class);
Route::apiResource('revisoes', RevisaoController::class);
Route::delete('/revisoes/{id}', [RevisaoController::class, 'destroy']);

// Rota para relatórios de veículos
Route::get('/relatorios/veiculos', [RelatorioController::class, 'veiculos']);
Route::get('/relatorios/veiculos-por-genero', [RelatorioController::class, 'veiculosPorGenero']);
Route::get('/relatorios/marcas-por-veiculos', [RelatorioController::class, 'marcasPorVeiculos']);
Route::get('/relatorios/media-tempo-revisoes', [RelatorioController::class, 'mediaTempoRevisoes']);

// Exemplo de rota protegida por autenticação (descomente se usar auth:sanctum)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
