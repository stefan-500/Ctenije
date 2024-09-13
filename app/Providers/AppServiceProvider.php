<?php

namespace App\Providers;

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
