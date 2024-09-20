<?php

use App\Http\Controllers\KnjigaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PorudzbinaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacanjeController;
use App\Http\Controllers\AdminController;

// Knjige
Route::get('/', [KnjigaController::class, 'index']);
Route::get('/knjige', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/{vrstaArtikla}', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/knjiga/{artikal_id}', [KnjigaController::class, 'show']);

// Cart
Route::get('/cart', [PorudzbinaController::class, 'index']);
Route::post('/add-to-cart', [PorudzbinaController::class, 'addToCart']);
Route::get('/cart-count', [PorudzbinaController::class, 'getCartCount']);
Route::post('/cart/increment', [PorudzbinaController::class, 'incrementQuantity']);
Route::post('/cart/decrement', [PorudzbinaController::class, 'decrementQuantity']);
Route::post('/cart/remove', [PorudzbinaController::class, 'removeFromCart']);

// Dostava
Route::get('set-delivery-step', [PorudzbinaController::class, 'setDeliveryStep']);
Route::get('/dostava', [PorudzbinaController::class, 'showDeliveryForm'])->middleware('checkDeliveryStep');
Route::post('/dostava', [PorudzbinaController::class, 'sacuvajPodatkeDostave']);

// Placanje
Route::get('/placanje', [PlacanjeController::class, 'prikazFormePlacanja']);
Route::post('/placanje', [PlacanjeController::class, 'obradaPlacanja']);
Route::get('/placanje/uspijeh', [PlacanjeController::class, 'placanjeUspijeh'])->name('placanje.uspijeh');
Route::post('/placanje/set-otkazano', [PlacanjeController::class, 'setPlacanjeOtkazano'])->name('placanje.otkazi');
Route::get('/placanje/otkazano', [PlacanjeController::class, 'placanjeOtkazano'])->name('placanje.otkazano');

// Admin Dashboard - pristup Menadzeru i Admin
Route::middleware(['auth', 'checkRole:Administrator,Menadzer'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/korisnici/index', [AdminController::class, 'sviKorisnici']);
    Route::delete('/korisnici/{user}', [AdminController::class, 'obrisiKorisnika']);
    Route::get('/menadzeri/index', [AdminController::class, 'sviMenadzeri']);

    Route::get('/artikli/index', [AdminController::class, 'sviArtikli']);
    Route::delete('/artikli/{artikal}', [AdminController::class, 'obrisiArtikal']);
    Route::get('/artikli/dodaj', [AdminController::class, 'dodajArtikal']);
    Route::post('/artikli/dodaj', [AdminController::class, 'sacuvajArtikal']);
    Route::get('/artikli/izmijeni/{artikal}', [AdminController::class, 'izmijeniArtikal']);
    Route::put('/artikli/izmijeni/{artikal}', [AdminController::class, 'azurirajArtikal']);

    Route::get('/porudzbine/index', [AdminController::class, 'svePorudzbine']);
    Route::get('/porudzbine/{porudzbina}', [AdminController::class, 'prikaziPorudzbinu']);

    // Pristup samo Adminu
    Route::middleware(['checkRole:Administrator'])->group(function () {
        Route::get('/menadzeri/dodaj', [AdminController::class, 'dodajMenadzera']);
        Route::post('/menadzeri/dodaj', [AdminController::class, 'sacuvajMenadzera']);
        Route::delete('/menadzeri/{user}', [AdminController::class, 'obrisiMenadzera']);
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
