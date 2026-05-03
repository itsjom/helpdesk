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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('name');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('kind', 32)->default('general');
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['network', 'Network / Internet', 10, true, 'general'],
            ['printer', 'Printer / Scanner', 20, true, 'general'],
            ['ups', 'UPS / Power', 30, true, 'general'],
            ['desktop_laptop', 'Desktop / Laptop', 40, true, 'general'],
            ['other', 'Other', 50, true, 'general'],
            ['recommendation', 'Hardware Recommendation', 60, true, 'recommendation'],
            ['disposal', 'Hardware Disposal', 70, true, 'disposal'],
        ];

        foreach ($rows as $r) {
            DB::table('service_types')->insert([
                'code' => $r[0],
                'name' => $r[1],
                'sort_order' => $r[2],
                'is_active' => $r[3],
                'kind' => $r[4],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::statement('ALTER TABLE tickets MODIFY service_type VARCHAR(64) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};
