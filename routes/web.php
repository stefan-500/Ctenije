<?php

use App\Http\Controllers\KnjigaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PorudzbinaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacanjeController;

Route::get('/', [KnjigaController::class, 'index']);
Route::get('/knjige', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/{vrstaArtikla}', [KnjigaController::class, 'listaKnjiga']);
Route::get('/knjige/knjiga/{artikal_id}', [KnjigaController::class, 'show']);

Route::get('/cart', [PorudzbinaController::class, 'index']);
Route::post('/add-to-cart', [PorudzbinaController::class, 'addToCart']);
Route::get('/cart-count', [PorudzbinaController::class, 'getCartCount']);
Route::post('/cart/increment', [PorudzbinaController::class, 'incrementQuantity']);
Route::post('/cart/decrement', [PorudzbinaController::class, 'decrementQuantity']);
Route::post('/cart/remove', [PorudzbinaController::class, 'removeFromCart']);

Route::get('set-delivery-step', [PorudzbinaController::class, 'setDeliveryStep']);
Route::get('/dostava', [PorudzbinaController::class, 'showDeliveryForm'])->middleware('checkDeliveryStep');
Route::post('/dostava', [PorudzbinaController::class, 'sacuvajPodatkeDostave']);
// Route::get('/stripe-payment', function () {
//     return view('stripe-payment');
// });

Route::get('/placanje', [PlacanjeController::class, 'prikazFormePlacanja']);
Route::post('/placanje', [PlacanjeController::class, 'obradaPlacanja']);
Route::get('/placanje/uspijeh', [PlacanjeController::class, 'placanjeUspijeh'])->name('placanje.uspijeh');
Route::get('/placanje/otkazano', [PlacanjeController::class, 'placanjeOtkazano'])->name('placanje.otkazano');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
