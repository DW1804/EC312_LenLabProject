<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Helpers\SettingsHelper;

class SettingsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with([
            'siteName' => SettingsHelper::siteName(),
            'primaryColor' => SettingsHelper::primaryColor(),
            'logoUrl' => SettingsHelper::logoUrl(),
            'faviconUrl' => SettingsHelper::faviconUrl(),
            'dynamicCss' => SettingsHelper::generateDynamicCss(),
        ]);
    }
}