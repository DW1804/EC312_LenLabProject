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
        Schema::table('orders', function (Blueprint $table) {
            // Remove order_code column
            if (Schema::hasColumn('orders', 'order_code')) {
                $table->dropUnique('orders_order_code_unique'); // Drop unique constraint first
                $table->dropColumn('order_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add back order_code column
            $table->string('order_code')->unique()->after('user_id');
        });
    }
};
