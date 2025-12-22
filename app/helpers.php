<?php

if (!function_exists('getSiteName')) {
    function getSiteName() {
        return \App\Helpers\SettingsHelper::siteName();
    }
}

if (!function_exists('getPrimaryColor')) {
    function getPrimaryColor() {
        return \App\Helpers\SettingsHelper::primaryColor();
    }
}

if (!function_exists('getLogoUrl')) {
    function getLogoUrl() {
        return \App\Helpers\SettingsHelper::logoUrl();
    }
}

if (!function_exists('getFaviconUrl')) {
    function getFaviconUrl() {
        return \App\Helpers\SettingsHelper::faviconUrl();
    }
}

if (!function_exists('getDynamicCss')) {
    function getDynamicCss() {
        return \App\Helpers\SettingsHelper::generateDynamicCss();
    }
}