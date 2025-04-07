<?php

namespace Database\Seeders;

use App\Models\OrganizationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Industry','Captive Power'];

        foreach ($categories as $key => $category) {
            OrganizationCategory::updateOrCreate([
                "name" => $category,
                "sorting_index" => $key,
            ]);
        }
    }
}
