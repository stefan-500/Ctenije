<?php

namespace App\Services;

use App\Models\Porudzbina;
use Illuminate\Support\Facades\Auth;

class CartService
{
  public function getCartCount()
  {
    $cartCount = 0;

    if (Auth::check()) {
      $porudzbina = Porudzbina::where('user_id', Auth::id())
        ->where('status', 'neobradjeno')
        ->with('stavkePorudzbine.artikal')
        ->first();

      $cartCount = $porudzbina ? $porudzbina->stavkePorudzbine->sum('kolicina') : 0;
    } else {
      // Neprijavljeni korisnik
      $cart = session()->get('cart', []);
      $cartCount = array_sum(array_column($cart, 'kolicina'));
    }

    return $cartCount;

  }
}