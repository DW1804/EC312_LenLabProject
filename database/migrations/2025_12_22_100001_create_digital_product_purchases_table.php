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
        Schema::create('digital_product_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_product_id')->constrained()->onDelete('cascade');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('order_code')->unique();
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at')->nullable();
            $table->integer('downloads_count')->default(0);
            $table->boolean('email_sent')->default(false);
            $table->json('download_history')->nullable(); // Lịch sử tải xuống
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_product_purchases');
    }
};