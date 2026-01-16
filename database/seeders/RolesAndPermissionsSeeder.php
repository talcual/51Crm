<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
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
            // Lead permissions
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',
            
            // Client permissions
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            
            // Deal permissions
            'view deals',
            'create deals',
            'edit deals',
            'delete deals',
            
            // Quote permissions
            'view quotes',
            'create quotes',
            'edit quotes',
            'delete quotes',
            'send quotes',
            
            // Payment permissions
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            
            // Follow-up permissions
            'view follow-ups',
            'create follow-ups',
            'edit follow-ups',
            'delete follow-ups',
            
            // Loyalty permissions
            'view loyalty',
            'manage loyalty',
            
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role and permission management
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Manager role - can manage most things except users and roles
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view leads', 'create leads', 'edit leads', 'delete leads',
            'view clients', 'create clients', 'edit clients', 'delete clients',
            'view deals', 'create deals', 'edit deals', 'delete deals',
            'view quotes', 'create quotes', 'edit quotes', 'delete quotes', 'send quotes',
            'view payments', 'create payments', 'edit payments',
            'view follow-ups', 'create follow-ups', 'edit follow-ups', 'delete follow-ups',
            'view loyalty', 'manage loyalty',
        ]);

        // Sales Rep role - can manage leads, clients, deals, and quotes
        $salesRep = Role::create(['name' => 'sales_rep']);
        $salesRep->givePermissionTo([
            'view leads', 'create leads', 'edit leads',
            'view clients', 'create clients', 'edit clients',
            'view deals', 'create deals', 'edit deals',
            'view quotes', 'create quotes', 'edit quotes', 'send quotes',
            'view follow-ups', 'create follow-ups', 'edit follow-ups',
            'view loyalty',
        ]);

        // Support role - can view most things and manage follow-ups
        $support = Role::create(['name' => 'support']);
        $support->givePermissionTo([
            'view leads', 'view clients', 'view deals', 'view quotes',
            'view follow-ups', 'create follow-ups', 'edit follow-ups',
            'view loyalty',
        ]);

        // Create a default admin user
        $adminUser = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@51crm.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('admin');
    }
}
