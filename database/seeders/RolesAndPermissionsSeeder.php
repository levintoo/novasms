<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage users']);

        Permission::create(['name' => 'manage admins']);

        $role = Role::create(['name' => 'admin']);

        $role->givePermissionTo('manage users');

        Role::create(['name' => 'super admin']);

        Role::create(['name' => 'standard user']);
    }
}
