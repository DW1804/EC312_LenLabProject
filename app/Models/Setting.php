<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $cacheKey = "setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            // Convert value based on type
            return self::convertValue($setting->value, $setting->type);
        });
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value, $type = 'string')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");

        return $setting;
    }

    /**
     * Convert value based on type
     */
    private static function convertValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            default:
                return $value;
        }
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAll()
    {
        return Cache::remember('all_settings', 3600, function () {
            $settings = self::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::convertValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('all_settings');
        
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }
}