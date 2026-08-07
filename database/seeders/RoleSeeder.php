<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage tickets',
            'manage users',
            'manage departments',
            'manage service types',
            'manage faqs',
            'view reports',
            'manage roles'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);

        // Give all permissions to admin
        $adminRole->syncPermissions($permissions);

        // Migrate existing users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole($adminRole);
            } else {
                $user->assignRole($userRole);
            }
        }
    }
}
