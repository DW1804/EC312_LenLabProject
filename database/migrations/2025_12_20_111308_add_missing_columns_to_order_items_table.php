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
            // Add missing columns
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }
            
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }
            
            if (!Schema::hasColumn('order_items', 'variant_info')) {
                $table->json('variant_info')->nullable()->after('variant_id');
            }
            
            if (!Schema::hasColumn('order_items', 'total')) {
                $table->decimal('total', 10, 2)->after('price');
            }
            
            if (!Schema::hasColumn('order_items', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $columnsToCheck = [
                'updated_at',
                'created_at', 
                'total',
                'variant_info',
                'product_image',
                'product_name'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
