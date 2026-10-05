<?php

namespace Tests\Feature;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaintenanceAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_electronically_submit_maintenance_request(): void
    {
        Storage::fake('public');
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Garden Villa 3',
            'slug' => 'garden-villa-3',
            'location' => 'Garden Way',
            'city' => 'Metropolis',
            'property_type' => 'Villa',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rental_price' => 2000.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $photo = UploadedFile::fake()->image('leak.jpg');

        $response = $this->actingAs($tenant)->post(route('maintenance.store'), [
            'property_id' => $property->id,
            'category' => 'Plumbing',
            'urgency' => 'high',
            'title' => 'Kitchen Sink Drain Pipe Leaking',
            'description' => 'Water is dripping from the P-trap whenever the kitchen faucet runs.',
            'photo' => $photo,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('maintenance_requests', [
            'tenant_id' => $tenant->id,
            'category' => 'Plumbing',
            'urgency' => 'high',
            'title' => 'Kitchen Sink Drain Pipe Leaking',
            'status' => 'pending',
        ]);

        // Landlord should receive notification
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $landlord->id,
            'type' => 'maintenance',
        ]);
    }

    public function test_landlord_can_update_maintenance_ticket_and_notify_tenant(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Duplex 8A',
            'slug' => 'duplex-8a',
            'location' => '8th Street',
            'city' => 'Metropolis',
            'property_type' => 'Duplex',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rental_price' => 1800.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant->id,
        ]);

        $ticket = MaintenanceRequest::create([
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'title' => 'HVAC Thermostat Malfunction',
            'category' => 'HVAC / Air Conditioning',
            'urgency' => 'medium',
            'description' => 'Thermostat display does not turn on.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($landlord)->patch(route('admin.maintenance.updateStatus', $ticket), [
            'status' => 'in_progress',
            'scheduled_date' => now()->addDays(2)->toDateString(),
            'technician_notes' => 'Electrician assigned for Thursday 10 AM.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('in_progress', $ticket->fresh()->status);
        $this->assertEquals('Electrician assigned for Thursday 10 AM.', $ticket->fresh()->technician_notes);

        // Tenant receives notification
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $tenant->id,
            'type' => 'maintenance',
        ]);
    }

    public function test_landlord_can_broadcast_announcements(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant1 = User::factory()->create(['role' => 'tenant']);
        $tenant2 = User::factory()->create(['role' => 'tenant']);

        $response = $this->actingAs($landlord)->post(route('admin.announcements.store'), [
            'target' => 'all_tenants',
            'title' => 'Water Supply Maintenance on Friday',
            'message' => 'The main municipal water valve will be flushed on Friday between 1 PM and 3 PM.',
        ]);

        $response->assertRedirect(route('notifications.index'));

        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $tenant1->id,
            'type' => 'announcement',
            'title' => 'Water Supply Maintenance on Friday',
        ]);

        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $tenant2->id,
            'type' => 'announcement',
            'title' => 'Water Supply Maintenance on Friday',
        ]);
    }

    public function test_user_can_mark_notifications_as_read(): void
    {
        $user = User::factory()->create();

        $notification = SystemNotification::create([
            'user_id' => $user->id,
            'title' => 'Test Notification',
            'message' => 'Hello!',
            'type' => 'system',
        ]);

        $this->assertNull($notification->read_at);

        $this->actingAs($user)->post(route('notifications.read', $notification));

        $this->assertNotNull($notification->fresh()->read_at);
    }
}
