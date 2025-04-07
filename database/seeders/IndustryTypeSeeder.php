<?php

namespace Database\Seeders;

use App\Models\IndustryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Government','Non-Government','Autonomous','Private Ownership','PartnerShip'];

        foreach ($types as $key => $type) {
            IndustryType::updateOrCreate([
                "name" => $type,
                "sorting_index" => $key +1,
            ]);
        }
    }
}
