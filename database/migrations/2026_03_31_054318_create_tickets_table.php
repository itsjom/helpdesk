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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('service_type', ['network', 'printer', 'ups', 'desktop_laptop', 'other']);
            $table->text('description');
            $table->enum('priority', ['high', 'medium', 'low']);
            $table->datetime('due_date');
            $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_remarks')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_tickets_status');
            $table->index('priority', 'idx_tickets_priority');
            $table->index('service_type', 'idx_tickets_service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
