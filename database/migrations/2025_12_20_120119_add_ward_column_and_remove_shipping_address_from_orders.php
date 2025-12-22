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
            // Add ward column
            if (!Schema::hasColumn('orders', 'ward')) {
                $table->string('ward', 100)->nullable()->after('province');
            }
            
            // Remove shipping_address column
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add back shipping_address column
            $table->json('shipping_address')->nullable();
            
            // Remove ward column
            if (Schema::hasColumn('orders', 'ward')) {
                $table->dropColumn('ward');
            }
        });
    }
};
