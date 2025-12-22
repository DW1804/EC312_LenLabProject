<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Lenlab Official',
                'type' => 'string',
                'description' => 'Tên website',
            ],
            [
                'key' => 'primary_color',
                'value' => '#D1A272',
                'type' => 'string',
                'description' => 'Màu sắc chủ đạo',
            ],
            [
                'key' => 'logo_path',
                'value' => null,
                'type' => 'file',
                'description' => 'Đường dẫn logo website',
            ],
            [
                'key' => 'favicon_path',
                'value' => null,
                'type' => 'file',
                'description' => 'Đường dẫn favicon',
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Bật thông báo email',
            ],
            [
                'key' => 'browser_notifications',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Bật thông báo trình duyệt',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}