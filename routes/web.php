<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptoController;

Route::get('/', [CryptoController::class, 'index'])->name('crypto.index');
Route::get('/search', [CryptoController::class, 'search'])->name('crypto.search');
Route::get('/coin/{id}', [CryptoController::class, 'show'])->name('crypto.show');

