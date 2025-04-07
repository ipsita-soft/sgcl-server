<?php

namespace Database\Seeders;

use App\Models\OrganizationOwnershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationOwnershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Sole Proprietorship', 'Joint Ownership', 'Limited Company', 'Others'];

        foreach ($types as $key => $type) {
            OrganizationOwnershipType::updateOrCreate([
                "name" => $type,
                "sorting_index" => $key +1,
            ]);
        }
    }
}
