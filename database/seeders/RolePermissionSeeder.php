<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define the modules
        $modules = [
            'users',
            'contacts',
            'profile',
        ];

        // Actions for each module
        $actions = ['list', 'view', 'add', 'edit', 'update', 'delete'];

        // Create permissions for each module and action
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action.$module"]);
            }
        }

        // Create roles
        $roles = [
            'admin' => Permission::all(), // Admin has all permissions
            // 'editor' => Permission::where('name', 'LIKE', 'edit%')->get(),
            // 'viewer' => Permission::where('name', 'LIKE', 'view%')->get(),
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }

        // Optional: Assign roles to specific users (example)
        $adminUser = \App\Models\User::find(1); // Replace with a valid user ID
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
