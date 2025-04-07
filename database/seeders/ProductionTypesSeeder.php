<?php

namespace Database\Seeders;

use App\Models\ProductionTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Spontaneous'];

        foreach ($types as $key => $type) {
            ProductionTypes::updateOrCreate([
                "name" => $type,
                "sorting_index" => $key +1,
            ]);
        }
    }
}
