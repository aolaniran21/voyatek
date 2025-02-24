<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        // Get the admin role
        $userRole = Role::where('name', 'User')->first();

        // Attach the admin role to the user
        $user->roles()->attach($userRole);


        $viewer = User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => bcrypt('viewer123'),
        ]);

        // Get the admin role
        $viewerRole = Role::where('name', 'Viewer')->first();

        // Attach the admin role to the user
        $viewer->roles()->attach($viewerRole);
    }
}
