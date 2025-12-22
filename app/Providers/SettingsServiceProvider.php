<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\SettingsHelper;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register settings helper as singleton
        $this->app->singleton('settings', function () {
            return new SettingsHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $view->with([
                'siteName' => SettingsHelper::siteName(),
                'primaryColor' => SettingsHelper::primaryColor(),
                'logoUrl' => SettingsHelper::logoUrl(),
                'faviconUrl' => SettingsHelper::faviconUrl(),
                'dynamicCss' => SettingsHelper::generateDynamicCss(),
            ]);
        });
    }
}