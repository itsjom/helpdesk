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
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->enum('old_status', ['pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled'])->nullable();
            $table->enum('new_status', ['pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled']);
            $table->text('remarks')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id', 'idx_ticket_logs_ticket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};
