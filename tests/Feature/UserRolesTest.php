<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_are_regular_users_by_default(): void
    {
        $user = User::factory()->create();

        $this->assertSame('gebruiker', $user->role);
    }

    public function test_regular_user_cannot_open_admin_page(): void
    {
        $user = User::factory()->create(['role' => 'gebruiker']);

        $this->actingAs($user)->get('/admin/gebruikers')->assertForbidden();
    }

    public function test_admin_can_open_admin_page_and_change_a_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'gebruiker']);

        $this->actingAs($admin)
            ->patch(route('admin.users.role', $user), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);
    }
}
