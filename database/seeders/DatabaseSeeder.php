<?php

namespace Database\Seeders;

use App\Models\IndustryType;
use App\Models\OrganizationCategory;
use App\Models\OrganizationOwnershipType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            OrganizationCategorySeeder::class,
            OrganizationOwnershipTypeSeeder::class,
            IndustryTypeSeeder::class,
            LandOwnershipSeeder::class,
            ProductionTypesSeeder::class,
            OrganizationSeeder::class,
            LocationSeeder::class,
            SettingsSeeder::class,
            ExternalClientAccessTokenSeeder::class,
        ]);

    }
}
