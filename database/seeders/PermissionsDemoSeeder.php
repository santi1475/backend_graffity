<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['guard_name' => 'api','name' => 'dashboard']);
        // create permissions
        Permission::create(['guard_name' => 'api','name' => 'register_role']);
        Permission::create(['guard_name' => 'api','name' => 'list_role']);
        Permission::create(['guard_name' => 'api','name' => 'edit_role']);
        Permission::create(['guard_name' => 'api','name' => 'delete_role']);

        Permission::create(['guard_name' => 'api','name' => 'register_user']);
        Permission::create(['guard_name' => 'api','name' => 'list_user']);
        Permission::create(['guard_name' => 'api','name' => 'edit_user']);
        Permission::create(['guard_name' => 'api','name' => 'delete_user']);

        Permission::create(['guard_name' => 'api','name' => 'register_categorie']);
        Permission::create(['guard_name' => 'api','name' => 'edit_categorie']);
        Permission::create(['guard_name' => 'api','name' => 'delete_categorie']);
        Permission::create(['guard_name' => 'api','name' => 'list_categorie']);

        Permission::create(['guard_name' => 'api','name' => 'register_product']);
        Permission::create(['guard_name' => 'api','name' => 'list_product']);
        Permission::create(['guard_name' => 'api','name' => 'edit_product']);
        Permission::create(['guard_name' => 'api','name' => 'delete_product']);

        Permission::create(['guard_name' => 'api','name' => 'register_client']);
        Permission::create(['guard_name' => 'api','name' => 'list_client']);
        Permission::create(['guard_name' => 'api','name' => 'edit_client']);
        Permission::create(['guard_name' => 'api','name' => 'delete_client']);

        Permission::create(['guard_name' => 'api','name' => 'register_sale']);
        Permission::create(['guard_name' => 'api','name' => 'list_sale']);
        Permission::create(['guard_name' => 'api','name' => 'edit_sale']);
        Permission::create(['guard_name' => 'api','name' => 'delete_sale']);


        Permission::create(['guard_name' => 'api','name' => 'register_guia_remision']);
        Permission::create(['guard_name' => 'api','name' => 'list_guia_remision']);
        Permission::create(['guard_name' => 'api','name' => 'nota_electronica']);
        Permission::create(['guard_name' => 'api','name' => 'list_nota_electronica']);
        // create roles and assign existing permissions

        $role3 = Role::create(['guard_name' => 'api','name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'alphatech@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($role3);
    }

}
