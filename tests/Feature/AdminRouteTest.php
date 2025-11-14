<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Access denied. Admin privileges required.');
    }
}