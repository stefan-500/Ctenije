<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$ovlascenja  Ovlascenja za provjeru
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$ovlascenja)
    {
        // Korisnik mora biti prijavljen
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $korisnik = Auth::user();

        // Provjera da li je korisnikovo ovlascenje u odobrenim ovlascenjima
        if (!in_array($korisnik->ovlascenje, $ovlascenja)) {
            abort(403, 'Neovlašćeno.');
        }

        return $next($request);
    }
}
