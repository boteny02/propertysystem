<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_view_payment_channels_and_bills(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Metro Plaza Suite 201',
            'slug' => 'metro-plaza-suite-201',
            'location' => '201 Central St',
            'city' => 'Metropolis',
            'property_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'rental_price' => 1100.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $bill = Bill::create([
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'bill_type' => 'Electricity Bills',
            'title' => 'October Power Bill',
            'amount' => 125.00,
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($tenant)->get(route('payments.index'));

        $response->assertStatus(200);
        $response->assertSee('October Power Bill');
        $response->assertSee('Zenith International Bank');
        $response->assertSee('Cryptocurrency Payment Facility');
        $response->assertSee('USDT');
    }

    public function test_tenant_can_submit_payment_receipt(): void
    {
        Storage::fake('public');
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Unit 501',
            'slug' => 'unit-501',
            'location' => '5th Street',
            'city' => 'Metropolis',
            'property_type' => 'Studio',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'rental_price' => 800.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $bill = Bill::create([
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'bill_type' => 'House Rent',
            'title' => 'November Rent',
            'amount' => 800.00,
            'due_date' => now()->addDays(10)->toDateString(),
            'status' => 'unpaid',
        ]);

        $receiptFile = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($tenant)->post(route('payments.store'), [
            'bill_id' => $bill->id,
            'property_id' => $property->id,
            'bill_type' => 'House Rent',
            'amount' => 800.00,
            'payment_method' => 'Crypto (USDT TRC20)',
            'transaction_reference' => 'TXN-CRYPTO-94820194',
            'payment_date' => now()->toDateString(),
            'receipt' => $receiptFile,
            'tenant_notes' => 'Sent from Binance wallet.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'tenant_id' => $tenant->id,
            'amount' => 800.00,
            'status' => 'pending',
            'transaction_reference' => 'TXN-CRYPTO-94820194',
        ]);

        $this->assertEquals('pending_verification', $bill->fresh()->status);
    }

    public function test_landlord_can_verify_and_approve_payment(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Villa Aurora',
            'slug' => 'villa-aurora',
            'location' => 'Coast Road',
            'city' => 'Metropolis',
            'property_type' => 'Villa',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'rental_price' => 3000.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $bill = Bill::create([
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'bill_type' => 'Maintenance Charges',
            'title' => 'Elevator Maintenance Fee',
            'amount' => 200.00,
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => 'pending_verification',
        ]);

        $payment = Payment::create([
            'tenant_id' => $tenant->id,
            'property_id' => $property->id,
            'bill_id' => $bill->id,
            'bill_type' => 'Maintenance Charges',
            'amount' => 200.00,
            'payment_method' => 'Bank Transfer',
            'transaction_reference' => 'REF-ZEN-12345',
            'payment_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($landlord)->post(route('admin.payments.verify', $payment), [
            'admin_notes' => 'Confirmed in Zenith account.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('verified', $payment->fresh()->status);
        $this->assertEquals('paid', $bill->fresh()->status);

        // Verify tenant received notification
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $tenant->id,
            'type' => 'payment',
        ]);
    }

    public function test_landlord_can_issue_new_bill_to_tenant(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Studio 4B',
            'slug' => 'studio-4b',
            'location' => '4th Street',
            'city' => 'Metropolis',
            'property_type' => 'Studio',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'rental_price' => 700.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $response = $this->actingAs($landlord)->post(route('admin.bills.store'), [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'bill_type' => 'Water Utility',
            'title' => 'November Water & Waste Levy',
            'amount' => 45.00,
            'due_date' => now()->addDays(14)->toDateString(),
            'notes' => 'Water consumption breakdown attached.',
        ]);

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('bills', [
            'tenant_id' => $tenant->id,
            'title' => 'November Water & Waste Levy',
            'amount' => 45.00,
            'status' => 'unpaid',
        ]);
    }
}
