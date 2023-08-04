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

        // create permissions
        // admins only
        Permission::create(['name' => 'manage groups']);
        Permission::create(['name' => 'manage contacts']);
        Permission::create(['name' => 'manage messages']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'manage users']);

        // super admins only
        Permission::create(['name' => 'view admins']);
        Permission::create(['name' => 'manage admins']);


        $role = Role::create(['name' => 'admin']);

        $role->givePermissionTo('manage users');

        $role->givePermissionTo('manage groups');

        $role->givePermissionTo('manage contacts');

        $role->givePermissionTo('manage messages');

        $role->givePermissionTo('view admins');

        Role::create(['name' => 'super admin']);

        Role::create(['name' => 'standard user']);
    }
}
