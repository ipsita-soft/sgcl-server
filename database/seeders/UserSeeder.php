<?php

namespace Database\Seeders;

use App\Enums\ApplicationPaymentEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = Role::all();
        foreach ($roles as $role) {
            //$applicationFee = $role->name == 'applicant' ||  $role->name == 'member' ? ApplicationPaymentEnum::PAID : null;
            User::factory()->create([
                'name' => strtolower($role->name),
                'phone' => '123456789',
                'role_id' => $role->id,
                'application_fee'=>$role->name == 'applicant' ? ApplicationPaymentEnum::PAID : ( $role->name == 'member' ? ApplicationPaymentEnum::PAID : NULL),
                'email' => strtolower($role->name) . '@gmail.com',
                'remember_token' => Str::random(10),
                'is_verified' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
        }


    }
}
