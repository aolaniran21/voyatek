<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_roles()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::factory()->create(['name' => 'Admin']));

        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Manager']);

        $this->actingAs($admin)
            ->postJson("/api/users/{$user->id}/assign-role", ['role_id' => $role->id])
            ->assertStatus(200)
            ->assertJson(['message' => 'Role assigned successfully']);
    }

    public function test_non_admin_cannot_assign_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Manager']);

        $this->actingAs($user)
            ->postJson("/api/users/{$user->id}/assign-role", ['role_id' => $role->id])
            ->assertStatus(403);
    }
}
