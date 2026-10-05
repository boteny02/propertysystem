<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_register_successfully(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Alice Tenant',
            'email' => 'alice@example.com',
            'phone' => '+1555123456',
            'role' => 'tenant',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'alice@example.com',
            'role' => 'tenant',
        ]);
    }

    public function test_landlord_can_register_successfully(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Bob Landlord',
            'email' => 'bob@example.com',
            'role' => 'landlord',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'bob@example.com',
            'role' => 'landlord',
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'role' => 'tenant',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_tenant_is_forbidden_from_landlord_administrative_routes(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);

        $response = $this->actingAs($tenant)->get(route('admin.properties.create'));

        $response->assertStatus(403);
    }

    public function test_landlord_can_access_administrative_routes(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord']);

        $response = $this->actingAs($landlord)->get(route('admin.properties.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Property to Catalogue');
    }

    public function test_quick_demo_login_works_for_roles(): void
    {
        $landlord = User::factory()->create(['role' => 'landlord', 'email' => 'landlord@test.com']);
        $tenant = User::factory()->create(['role' => 'tenant', 'email' => 'tenant@test.com']);

        $responseLandlord = $this->get(route('demo.login', 'landlord'));
        $responseLandlord->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($landlord);

        $this->post(route('logout'));
        $this->assertGuest();

        $responseTenant = $this->get(route('demo.login', 'tenant'));
        $responseTenant->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($tenant);
    }
}
