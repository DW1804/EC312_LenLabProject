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
            // Add order_code column if it doesn't exist
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->unique()->after('user_id');
            }
            
            // Add other missing columns if they don't exist
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('processing')->after('order_code');
            }
            
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('payment_status');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_fee')) {
                $table->decimal('shipping_fee', 10, 2)->after('subtotal');
            }
            
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_fee');
            }
            
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->after('discount_amount');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->json('shipping_address')->after('total');
            }
            
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('shipping_address');
            }
            
            if (!Schema::hasColumn('orders', 'transfer_image')) {
                $table->string('transfer_image')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop columns in reverse order
            $columnsToCheck = [
                'transfer_image',
                'notes', 
                'shipping_address',
                'total',
                'discount_amount',
                'shipping_fee',
                'subtotal',
                'payment_status',
                'payment_method',
                'status',
                'order_code'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
