<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Helpers\SettingsHelper;

class InjectSettings
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Share settings with all views
        View::share([
            'siteName' => SettingsHelper::siteName(),
            'primaryColor' => SettingsHelper::primaryColor(),
            'logoUrl' => SettingsHelper::logoUrl(),
            'faviconUrl' => SettingsHelper::faviconUrl(),
            'dynamicCss' => SettingsHelper::generateDynamicCss(),
        ]);

        return $next($request);
    }
}