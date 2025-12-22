<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;

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
        // Share settings with specific views
        View::composer([
            'admin.*',
            'layouts.*',
            'landingpage',
            'products',
            'product',
            'intro',
            'cart',
            'checkout*',
            'auth.*'
        ], SettingsComposer::class);
    }
}
