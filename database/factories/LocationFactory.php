<?php

namespace Database\Factories;

use App\Models\LandOwnership;
use App\Models\Location;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $organization =  Organization::inRandomOrder()->first()->id;
        $landOwnership =  LandOwnership::inRandomOrder()->first()->id;
        return [
            'organization_id' => $organization,
            'mouza_name' => $this->faker->word,
            'daag_no' => $this->faker->word,
            'khotiyan_no' => $this->faker->word,
            'total_land_area' => $this->faker->randomFloat(2, 10, 100),
            'land_ownership_id' =>$landOwnership,
            'land_width_feet' => $this->faker->numberBetween(1, 100),
            'land_length_feet' => $this->faker->numberBetween(1, 100),
            'owner_name_ifRented' => $this->faker->name,
            'lease_provider_organization_name_Ifleased' => $this->faker->company,
            'lease_provider_organization_address_if_leased' => $this->faker->address,
            'any_other_customer_used_gas' => $this->faker->boolean(50) ? 1 : 2,
            'customer_code_no' => $this->faker->randomNumber(5),
            'organization_name' => $this->faker->company,
            'customer_name' => $this->faker->name,
            'connection_status' => $this->faker->numberBetween(1, 3),
            'clearance_of_gas_bill' => $this->faker->boolean(50) ? 1 : 2,
            'is_organization_owner' => $this->faker->boolean(50) ? 1 : 2,
            'owner_partner_code' => $this->faker->unique()->ean8,
            'owner_partner_name' => $this->faker->name,
            'owner_partner_status' => $this->faker->word,
        ];
    }
}
