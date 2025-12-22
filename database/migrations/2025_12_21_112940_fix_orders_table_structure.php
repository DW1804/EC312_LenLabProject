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
            // Ensure order_id is the primary key and string type
            if (!Schema::hasColumn('orders', 'order_id')) {
                $table->string('order_id')->primary()->after('id');
            }
            
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('orders', 'full_name')) {
                $table->string('full_name')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('full_name');
            }
            
            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('orders', 'specific_address')) {
                $table->text('specific_address')->nullable()->after('ward');
            }
            
            if (!Schema::hasColumn('orders', 'order_note')) {
                $table->text('order_note')->nullable()->after('specific_address');
            }
            
            // Rename total to total_amount if needed
            if (Schema::hasColumn('orders', 'total') && !Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total', 'total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove added columns
            $columnsToRemove = [
                'order_id',
                'email',
                'full_name', 
                'phone',
                'province',
                'specific_address',
                'order_note'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Rename back total_amount to total
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_amount', 'total');
            }
        });
    }
};