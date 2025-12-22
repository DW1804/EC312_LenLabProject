<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsHelper
{
    /**
     * Get setting value with fallback
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', 'Lenlab Official');
    }

    /**
     * Get primary color
     */
    public static function primaryColor()
    {
        return self::get('primary_color', '#D1A272');
    }

    /**
     * Get logo URL
     */
    public static function logoUrl()
    {
        $logoPath = self::get('logo_path');
        return $logoPath ? Storage::url($logoPath) : null;
    }

    /**
     * Get favicon URL
     */
    public static function faviconUrl()
    {
        $faviconPath = self::get('favicon_path');
        return $faviconPath ? Storage::url($faviconPath) : asset('favicon.ico');
    }

    /**
     * Check if email notifications are enabled
     */
    public static function emailNotificationsEnabled()
    {
        return self::get('email_notifications', true);
    }

    /**
     * Check if browser notifications are enabled
     */
    public static function browserNotificationsEnabled()
    {
        return self::get('browser_notifications', false);
    }

    /**
     * Get CSS variables for dynamic styling
     */
    public static function getCssVariables()
    {
        $primaryColor = self::primaryColor();
        
        // Convert hex to RGB for opacity variations
        $rgb = self::hexToRgb($primaryColor);
        
        return [
            '--color-primary' => $primaryColor,
            '--color-primary-rgb' => implode(', ', $rgb),
            '--color-primary-hover' => self::adjustBrightnessPrivate($primaryColor, -20),
            '--color-primary-light' => self::adjustBrightnessPrivate($primaryColor, 40),
        ];
    }

    /**
     * Convert hex color to RGB array
     */
    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    /**
     * Adjust color brightness (public method)
     */
    public static function adjustBrightness($hex, $steps)
    {
        $steps = max(-255, min(255, $steps));
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color);
            $color = max(0, min(255, $color + $steps));
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
        }

        return $return;
    }

    /**
     * Adjust color brightness (private method)
     */
    private static function adjustBrightnessPrivate($hex, $steps)
    {
        return self::adjustBrightness($hex, $steps);
    }

    /**
     * Generate dynamic CSS for the application
     */
    public static function generateDynamicCss()
    {
        $variables = self::getCssVariables();
        $css = ':root {';
        
        foreach ($variables as $property => $value) {
            $css .= "\n    {$property}: {$value};";
        }
        
        $css .= "\n}";
        
        // Add additional dynamic styles
        $primaryColor = self::primaryColor();
        $css .= "\n\n";
        $css .= ".bg-primary { background-color: {$primaryColor} !important; }\n";
        $css .= ".text-primary { color: {$primaryColor} !important; }\n";
        $css .= ".border-primary { border-color: {$primaryColor} !important; }\n";
        $css .= ".hover\\:bg-primary:hover { background-color: " . self::adjustBrightness($primaryColor, -20) . " !important; }\n";
        
        return $css;
    }
}