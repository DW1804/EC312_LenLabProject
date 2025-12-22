<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('type')->default('file'); // file, link, course
            $table->json('files')->nullable(); // Lưu danh sách file
            $table->json('links')->nullable(); // Lưu danh sách link
            $table->text('instructions')->nullable(); // Hướng dẫn sử dụng
            $table->boolean('auto_send_email')->default(false);
            $table->text('email_template')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('download_limit')->default(3); // Giới hạn tải xuống
            $table->integer('access_days')->default(30); // Số ngày truy cập
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_products');
    }
};