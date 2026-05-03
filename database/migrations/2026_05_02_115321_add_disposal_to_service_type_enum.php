<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN service_type ENUM('network', 'printer', 'ups', 'desktop_laptop', 'other', 'recommendation', 'disposal') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN service_type ENUM('network', 'printer', 'ups', 'desktop_laptop', 'other', 'recommendation') NOT NULL");
        });
    }
};
