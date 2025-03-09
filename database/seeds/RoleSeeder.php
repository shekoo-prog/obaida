<?php

namespace Database\Seeds;

use App\Models\Role;
use App\Models\Permission;

class RoleSeeder
{
    public function run()
    {
        $role = new Role();
        $permission = new Permission();

        // Create admin role
        $adminRole = $role->create([
            'name' => 'admin',
            'description' => 'System Administrator'
        ]);

        // Create user role
        $userRole = $role->create([
            'name' => 'user',
            'description' => 'Regular User'
        ]);

        // Create permissions
        $permissions = [
            'view_dashboard' => 'Access dashboard',
            'manage_users' => 'Manage users',
            'manage_roles' => 'Manage roles',
            'manage_permissions' => 'Manage permissions'
        ];

        foreach ($permissions as $name => $description) {
            $perm = $permission->create([
                'name' => $name,
                'description' => $description
            ]);
            
            // Assign all permissions to admin role
            $role->assignPermission($adminRole->id, $perm->id);
            
            // Assign only view_dashboard to user role
            if ($name === 'view_dashboard') {
                $role->assignPermission($userRole->id, $perm->id);
            }
        }
    }
}