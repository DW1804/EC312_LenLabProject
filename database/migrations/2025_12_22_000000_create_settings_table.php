<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, json, file
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'site_name',
                'value' => 'Lenlab Official',
                'type' => 'string',
                'description' => 'Tên website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'primary_color',
                'value' => '#D1A272',
                'type' => 'string',
                'description' => 'Màu sắc chủ đạo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'logo_path',
                'value' => null,
                'type' => 'file',
                'description' => 'Đường dẫn logo website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'favicon_path',
                'value' => null,
                'type' => 'file',
                'description' => 'Đường dẫn favicon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Bật thông báo email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'browser_notifications',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Bật thông báo trình duyệt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};