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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bill_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bill_type'); // House Rent, Electricity Bills, Maintenance Charges, Water Utility, etc.
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // Bank Transfer, Crypto (USDT TRC20), Crypto (Bitcoin BTC), Crypto (Ethereum ETH), etc.
            $table->string('transaction_reference')->nullable();
            $table->date('payment_date');
            $table->string('receipt_image')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('tenant_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
