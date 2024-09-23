<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('produtos')->group(function () {
    Route::get('/', [ProdutoController::class, 'index'])->name('produtos.index');                           # view
    Route::get('/listar/{id}', [ProdutoController::class, 'get'])->name('produtos.get');                    # detalhes de um produto
    Route::get('/listar', [ProdutoController::class, 'listar'])->name('produtos.listar');                   # lista de todos os produtos
    Route::get('/novo', [ProdutoController::class, 'novoProduto'])->name('produtos.novo');                  # view novo produto
    Route::post('/', [ProdutoController::class, 'store'])->name('produtos.store');                          # salva no banco
    Route::post('/update/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');        # atualiza no banco
    Route::post('/remover/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.remover');     # desativa
    Route::post('/restaurar/{produto}', [ProdutoController::class, 'restore'])->name('produtos.restore');   # restaura
});
