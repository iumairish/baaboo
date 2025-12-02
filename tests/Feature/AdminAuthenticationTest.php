<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    public function test_admin_can_view_login_page(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'testadmin@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'testadmin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.tickets.index'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_cannot_login_with_invalid_credentials(): void
    {
        Admin::factory()->create([
            'email' => 'testadmin2@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'testadmin2@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('admin');
    }

    public function test_admin_can_logout(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'testadmin3@test.com',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get(route('admin.tickets.index'));

        $response->assertRedirect(route('admin.login'));
    }
}
