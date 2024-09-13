<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDeliveryStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('nastavak_na_dostavu')) {
            // Redirect na cart ako korisnik nije ispostovao korak porudzbine
            return redirect('/cart')->with('error', __('Morate nastaviti na dostavu sa ove stranice.'));
        }

        return $next($request);
    }
}
