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
        Schema::table('order_items', function (Blueprint $table) {
            // Check if variant_id column exists, if not add it
            if (!Schema::hasColumn('order_items', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
                echo "Added variant_id column to order_items table\n";
            } else {
                echo "variant_id column already exists in order_items table\n";
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'variant_id')) {
                $table->dropColumn('variant_id');
            }
        });
    }
};