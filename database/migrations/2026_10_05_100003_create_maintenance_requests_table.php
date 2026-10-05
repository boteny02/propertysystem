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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('category'); // Plumbing, Electrical, Appliance, HVAC, Structural, Pest Control, Painting, Other
            $table->string('urgency')->default('medium'); // low, medium, high, emergency
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, resolved, cancelled
            $table->text('technician_notes')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
