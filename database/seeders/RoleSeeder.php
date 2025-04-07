<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create instance of Role model
        $role = new Role();
        // Call permissionsList() method from Role model to get permissions
        $permissions = $role->getPermissionSlugs();

        // Create Super Admin role and assign all permissions
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'permissions' => json_encode($permissions),
        ]);


        $admin = Role::create([
            'name' => 'Admin',
            'permissions' => json_encode([
                "dashboard.view",
                "user-application-request.list",
                "user-application-request.show",
                "user-application-request.create",
                "user-application-request.edit",
                "user-application-request.delete",


                "member-list",
                "member.show",
                "member.edit",

                "user-payments-request.view",
                "admin-payments-request.view",
                "message.view",
                "all-data.view",

                "applicationFrom.create",

            ]),
        ]);

        $member = Role::create([
            'name' => 'member',
            'permissions' => json_encode([
                "dashboard.view",
                "user-payments-request.view",
                "message.view",

            ]),
        ]);




        // Create Staff role and assign specific permissions
        $applicant = Role::create([
            'name' => 'applicant',
            'permissions' => json_encode([
                "dashboard.view",
                "applicationFrom.create",

            ]),
        ]);

    }
}
