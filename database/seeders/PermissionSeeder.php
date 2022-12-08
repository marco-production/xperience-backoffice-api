<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Create Roles */
        Role::create(['name' => 'Super Admin', 'guard_name' => 'api']);
        Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        Role::create(['name' => 'User', 'guard_name' => 'api']);
        Role::create(['name' => 'Politur', 'guard_name' => 'api']);

        //Role Permission
        Permission::create([
            'name' => 'roles.index',
            'alias' => 'Roles list',
            'group' => 'Roles',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'roles.create',
            'alias' => 'Create role',
            'group' => 'Roles',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'roles.show',
            'alias' => 'Show role',
            'group' => 'Roles',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'roles.update',
            'alias' => 'Update role',
            'group' => 'Roles',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'roles.delete',
            'alias' => 'Delete role',
            'group' => 'Roles',
            'guard_name' => 'api'
        ]);

        //User Permission
        Permission::create([
            'name' => 'user.index',
            'alias' => 'User list',
            'group' => 'Users',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'user.create',
            'alias' => 'Create user',
            'group' => 'Users',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'user.show',
            'alias' => 'Show user',
            'group' => 'Users',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'user.update',
            'alias' => 'Update user',
            'group' => 'Users',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'user.delete',
            'alias' => 'Delete user',
            'group' => 'Users',
            'guard_name' => 'api'
        ]);

        //Traveler Permission
        Permission::create([
            'name' => 'traveler.index',
            'alias' => 'Traveler list',
            'group' => 'Travelers',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.create',
            'alias' => 'Create traveler',
            'group' => 'Travelers',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.show',
            'alias' => 'Show traveler',
            'group' => 'Travelers',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.update',
            'alias' => 'Update traveler',
            'group' => 'Travelers',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.delete',
            'alias' => 'Delete traveler',
            'group' => 'Travelers',
            'guard_name' => 'api'
        ]);

        //Traveler Permission App
        Permission::create([
            'name' => 'traveler.index.app',
            'alias' => 'Traveler list',
            'group' => 'Travelers in the App',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.create.app',
            'alias' => 'Create traveler',
            'group' => 'Travelers in the App',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.show.app',
            'alias' => 'Show traveler',
            'group' => 'Travelers in the App',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.update.app',
            'alias' => 'Update traveler',
            'group' => 'Travelers in the App',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'traveler.delete.app',
            'alias' => 'Delete traveler',
            'group' => 'Travelers in the App',
            'guard_name' => 'api'
        ]);

        //Eticket Permission
        Permission::create([
            'name' => 'eticket.index',
            'alias' => 'Eticket list',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'eticket.create',
            'alias' => 'Create eticket',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'eticket.show',
            'alias' => 'Show eticket',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'eticket.show',
            'alias' => 'Show eticket',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'eticket.show.last',
            'alias' => 'Show last eticket',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'eticket.delete',
            'alias' => 'Delete eticket',
            'group' => 'Etickets',
            'guard_name' => 'api'
        ]);

        // Update profile
        Permission::create([
            'name' => 'profile.update',
            'alias' => 'Update profile',
            'group' => 'Profile',
            'guard_name' => 'api'
        ]);

        //Other
        Permission::create([
            'name' => 'user.deletion.reasons',
            'alias' => 'User deletion reasons',
            'group' => 'Back office',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'user.logs',
            'alias' => 'User Account Deletion Reasons',
            'group' => 'Back office',
            'guard_name' => 'api'
        ]);

        // Geolocation Permission
        Permission::create([
            'name' => 'geolocations.country',
            'alias' => 'Get countries',
            'group' => 'Geolocation',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'geolocations.city',
            'alias' => 'Get Cities',
            'group' => 'Geolocation',
            'guard_name' => 'api'
        ]);

        /* Assign Permission to Role */
        Role::find(2)->givePermissionTo([
            'user.index',
            'user.create',
            'user.show',
            'user.update',
            'user.delete'
        ]);

        Role::find(3)->givePermissionTo([
            'profile.update',
            'traveler.index.app',
            'traveler.create.app',
            'traveler.show.app',
            'traveler.update.app',
            'traveler.delete.app',
            'eticket.show.last',
            'eticket.create',
            'geolocations.country',
            'geolocations.city',
        ]);

    }
}
