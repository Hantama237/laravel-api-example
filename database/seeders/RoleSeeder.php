<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $partner = Role::create(['name' => 'partner']);
        $student = Role::create(['name' => 'student']);

        // Create basic permissions
        $permissions = [
            'manage_users',
            'manage_plans',
            'manage_subscriptions',
            'view_dashboard',
            'take_exams',
            'manage_students',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin->givePermissionTo($permissions);
        
        $admin->givePermissionTo([
            'manage_users',
            'manage_plans',
            'manage_subscriptions',
            'view_dashboard',
            'view_reports',
        ]);
        
        $partner->givePermissionTo([
            'manage_students',
            'view_dashboard',
            'view_reports',
        ]);
        
        $student->givePermissionTo([
            'take_exams',
            'view_dashboard',
        ]);
    }
}
