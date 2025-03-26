<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Ensure admin role exists
        $admin = Role::firstOrCreate(['name' => 'admin']);
        
        // Ensure super_admin role exists
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Add permissions
        $permissions = [
            'role.view',
            'role.create',
            'role.update',
            'role.delete',
            'task.view',
            'task.create',
            'task.update',
            'task.delete',
            'taskDetail.view',
            'taskDetail.create',
            'taskDetail.update',
            'taskDetail.delete',
            'taskWeekOverview.view',
            'taskWeekOverview.create',
            'taskWeekOverview.update',
            'taskWeekOverview.delete',
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm]);
            $admin->givePermissionTo($permission);
            $superAdmin->givePermissionTo($permission);
        }
    }
}
