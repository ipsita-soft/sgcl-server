<?php

namespace Database\Seeders;

use App\Models\LandOwnership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandOwnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $types = ['Own','Rent','Assignment','Allocated','Joint'];

        foreach ($types as $key => $type) {
            LandOwnership::updateOrCreate([
                "name" => $type,
                "sorting_index" => $key +1,
            ]);
        }
    }
}
