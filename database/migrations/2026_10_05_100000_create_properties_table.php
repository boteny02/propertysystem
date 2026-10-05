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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Landlord/admin
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location');
            $table->string('city');
            $table->string('property_type'); // Apartment, Studio, Duplex, Villa, Townhouse, etc.
            $table->unsignedInteger('bedrooms')->default(1);
            $table->unsignedInteger('bathrooms')->default(1);
            $table->json('facilities')->nullable(); // JSON array of amenities
            $table->decimal('rental_price', 12, 2);
            $table->string('rent_frequency')->default('monthly'); // monthly, quarterly, annually
            $table->string('status')->default('available'); // available, rented, maintenance
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->foreignId('current_tenant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
