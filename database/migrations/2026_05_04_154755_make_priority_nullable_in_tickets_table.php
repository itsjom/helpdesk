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
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('priority', ['high', 'medium', 'low'])->nullable()->change();
            $table->datetime('due_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('priority', ['high', 'medium', 'low'])->nullable(false)->change();
            $table->datetime('due_date')->nullable(false)->change();
        });
    }
};
