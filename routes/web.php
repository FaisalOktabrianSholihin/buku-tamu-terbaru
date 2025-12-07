<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuTamuController;

Route::get('/', function () {
    return view('form');
});
Route::get('/buku-tamu', [BukuTamuController::class, 'index'])->name('bukutamu.form');
Route::post('/buku-tamu', [BukuTamuController::class, 'store'])->name('bukutamu.store');
