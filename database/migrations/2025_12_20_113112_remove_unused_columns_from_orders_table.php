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
            // Remove unused columns as requested
            $columnsToRemove = [
                'notes',        // Sử dụng order_note thay thế
                'district',     // Không cần thiết
                'total',        // Sử dụng total_amount thay thế
                'shipping_method' // Không sử dụng
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add back removed columns
            $table->text('notes')->nullable();
            $table->string('district', 100)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('shipping_method', 50)->nullable();
        });
    }
};
