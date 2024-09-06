<?php

use App\Http\Controllers\KnjigaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KnjigaController::class, 'index']);

Route::get('/knjige', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/{vrstaArtikla}', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/knjiga/{artikal_id}', [KnjigaController::class, 'show']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
