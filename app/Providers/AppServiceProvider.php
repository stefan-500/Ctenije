<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Http\Middleware\CheckDeliveryStep;
use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\VrstaArtikala;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Porudzbina;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AccessAdmin;
use App\Http\Middleware\CheckRole;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Middleware za provjeru ovlascenja korisnika
        Route::aliasMiddleware('checkRole', CheckRole::class);

        // Definicija 'access-admin' Gate-a
        Gate::define('access-admin', function (User $user) {
            // Da li je instanca korisnika Menadzer/Administrator
            return in_array($user->ovlascenje, ['Menadzer', 'Administrator']);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikacija Email Adrese')
                ->greeting('Poštovani,')
                ->line('Kliknite dugme ispod da biste verifikovali Vašu email adresu.')
                ->action('Verifikacija Email Adrese', $url);
        });

        // Cini $vrsteArtikala i $cartCount vidljivim u nav.blade.php
        View::composer('components.nav', function ($view) {
            $vrsteArtikala = VrstaArtikala::all();

            $cartService = new CartService();
            $cartCount = $cartService->getCartCount();

            $view->with([
                'vrsteArtikala' => $vrsteArtikala,
                'cartCount' => $cartCount
            ]);
        });

        // Registracija middleware-a za pristup dostava.blade.php
        Route::aliasMiddleware('checkDeliveryStep', CheckDeliveryStep::class);
    }
}
