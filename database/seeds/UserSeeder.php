<?php

namespace Database\Seeds;

use App\Models\User;
use App\Models\Role;

class UserSeeder
{
    public function run()
    {
        $user = new User();
        $role = new Role();

        // Create admin user
        $adminUser = $user->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT)
        ]);

        // Create regular user
        $regularUser = $user->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => password_hash('user123', PASSWORD_DEFAULT)
        ]);

        // Assign roles
        $adminRole = $role->where('name', 'admin')->first();
        $userRole = $role->where('name', 'user')->first();

        if ($adminRole) {
            $user->assignRole($adminUser->id, $adminRole->id);
        }

        if ($userRole) {
            $user->assignRole($regularUser->id, $userRole->id);
        }
    }
}