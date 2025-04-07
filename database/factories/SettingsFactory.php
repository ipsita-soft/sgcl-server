<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Settings>
 */
class SettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => function () {
                return \App\Models\Organization::factory()->create()->id;
            },
            'module' => $this->faker->word,
            'name' => $this->faker->word,
            'data' => json_encode(['key' => 'value']), 
        ];
    }
}
