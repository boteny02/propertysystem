<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyCatalogueTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_users_can_browse_property_catalogue(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Sunset View Apartment',
            'slug' => 'sunset-view-apartment',
            'location' => '123 Beachfront Way',
            'city' => 'Metropolis',
            'property_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'rental_price' => 1500.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
            'facilities' => ['WiFi', 'Pool'],
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertSee('Sunset View Apartment');
        $response->assertSee('₦1,500.00/mo');
    }

    public function test_catalogue_search_filters_correctly(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);

        Property::create([
            'user_id' => $landlord->id,
            'name' => 'Downtown Modern Loft',
            'slug' => 'downtown-modern-loft',
            'location' => '5th Avenue',
            'city' => 'Downtown',
            'property_type' => 'Studio',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'rental_price' => 900.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
        ]);

        Property::create([
            'user_id' => $landlord->id,
            'name' => 'Highland Luxury Villa',
            'slug' => 'highland-luxury-villa',
            'location' => 'Mountain Road',
            'city' => 'Uptown',
            'property_type' => 'Villa',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'rental_price' => 4500.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
        ]);

        $searchResponse = $this->get(route('properties.index', ['q' => 'Highland']));
        $searchResponse->assertSee('Highland Luxury Villa');
        $searchResponse->assertDontSee('Downtown Modern Loft');

        $typeResponse = $this->get(route('properties.index', ['type' => 'Studio']));
        $typeResponse->assertSee('Downtown Modern Loft');
        $typeResponse->assertDontSee('Highland Luxury Villa');
    }

    public function test_landlord_can_create_new_property_with_image(): void
    {
        Storage::fake('public');
        $landlord = User::factory()->create(['role' => 'landlord']);

        $file = UploadedFile::fake()->image('property.jpg');

        $response = $this->actingAs($landlord)->post(route('admin.properties.store'), [
            'name' => 'Royal Garden Estate Unit 5',
            'location' => '77 Royal Avenue',
            'city' => 'Metropolis',
            'property_type' => 'Apartment',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rental_price' => 2200.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
            'description' => 'A wonderful family home with spacious rooms.',
            'facilities' => ['WiFi Internet', '24/7 Electricity', 'Dedicated Parking'],
            'image' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'name' => 'Royal Garden Estate Unit 5',
            'city' => 'Metropolis',
            'rental_price' => 2200.00,
        ]);

        $createdProperty = Property::where('name', 'Royal Garden Estate Unit 5')->first();
        $this->assertNotNull($createdProperty->featured_image);
        Storage::disk('public')->assertExists($createdProperty->featured_image);
    }

    public function test_landlord_can_assign_and_unassign_tenant(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);
        $tenant = User::factory()->create(['role' => 'tenant']);

        $property = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Park View Unit 10',
            'slug' => 'park-view-unit-10',
            'location' => '10 Park Avenue',
            'city' => 'Metropolis',
            'property_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'rental_price' => 1200.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
        ]);

        // Assign tenant
        $response = $this->actingAs($landlord)->post(route('admin.properties.assignTenant', $property), [
            'tenant_id' => $tenant->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'current_tenant_id' => $tenant->id,
            'status' => 'rented',
        ]);

        // Unassign tenant
        $response = $this->actingAs($landlord)->post(route('admin.properties.assignTenant', $property), [
            'tenant_id' => null,
        ]);

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'current_tenant_id' => null,
            'status' => 'available',
        ]);
    }
}
