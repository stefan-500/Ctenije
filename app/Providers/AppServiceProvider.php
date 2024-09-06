<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\VrstaArtikala;
use Illuminate\Support\Facades\View;

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

        // Cini $vrsteArtikala iz KnjigaController-a vidljivim u svim pogledima, ukljucujuci nav.blade.php
        View::composer('components.nav', function ($view) {
            $view->with('vrsteArtikala', VrstaArtikala::all());
        });
    }
}
