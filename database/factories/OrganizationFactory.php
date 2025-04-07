<?php

namespace Database\Factories;

use App\Models\IndustryType;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationOwnershipType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */



    public function definition(): array
    {

        $userId = User::whereDoesntHave('organization')->where('role_id',4)->inRandomOrder()->first()->id;
        $ownershipType = OrganizationOwnershipType::inRandomOrder()->first()->id;
        $category = OrganizationCategory::inRandomOrder()->first()->id;
        $industryType =IndustryType::inRandomOrder()->first()->id;
        return [

            'user_id' => $userId,
            'organization_category_id' => $category,

            'application_date' => $this->faker->date(),
            'factory_name' => $this->faker->company(),

            'factory_address' => $this->faker->address(),
            'factory_telephone' => $this->faker->phoneNumber(),
            'main_office_address' => $this->faker->address(),
            'main_office_telephone' => $this->faker->phoneNumber(),
            'billing_address' => $this->faker->address(),
            'billing_telephone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'national_id' => $this->faker->randomNumber(),
            'tax_identification_no' => $this->faker->randomNumber(),
            'gis_location' => $this->faker->latitude() . ',' . $this->faker->longitude(),

            'organization_ownership_type_id' => $ownershipType,
            'industry_type_id' => $industryType,

            'trade_license_no' => $this->faker->randomNumber(),
            'license_expiry_date' => $this->faker->date(),
            'applicants_name' => $this->faker->name(),
            'applicants_designation' => $this->faker->jobTitle(),
            'partner_customer_code_no' => $this->faker->randomNumber(),
            'other_organization_name' => $this->faker->company(),
            'other_organization_status' => $this->faker->randomElement(['Active', 'Inactive']),
        ];
    }
}
