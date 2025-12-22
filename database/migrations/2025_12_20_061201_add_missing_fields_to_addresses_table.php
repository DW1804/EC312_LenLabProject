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
        Schema::table('addresses', function (Blueprint $table) {
            // Add missing fields for address book functionality
            $table->string('full_name')->after('user_id');
            $table->string('phone', 20)->after('full_name');
            $table->string('specific_address', 500)->after('ward_id');
            $table->boolean('is_default')->default(false)->after('specific_address');
            
            // Rename detail to match the new structure (optional)
            // We'll keep both for now to avoid data loss
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone', 'specific_address', 'is_default']);
        });
    }
};