<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Logger\Loggable;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Middleware\AuthorizationMiddleware;

class AdminController extends BaseController
{
    use Loggable;

    public function __construct()
    {
        parent::__construct();
        $this->initLogger();
        $this->middleware(new AuthorizationMiddleware(), 'manage_users');
    }

    public function createRole()
    {
        try {
            $role = new Role();
            $roleData = $role->create([
                'name' => 'editor',
                'description' => 'Can edit content'
            ]);

            $permission = new Permission();
            $permissionData = $permission->create([
                'name' => 'edit_posts',
                'description' => 'Can edit posts'
            ]);

            $role->assignPermission($permissionData->id);

            $this->logActivity('Created new role and permission', [
                'role' => $roleData->name,
                'permission' => $permissionData->name
            ]);

            return $this->json(['message' => 'Role created successfully']);

        } catch (\Exception $e) {
            $this->logError('Failed to create role', [
                'error' => $e->getMessage()
            ]);
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}