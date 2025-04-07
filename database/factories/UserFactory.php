<?php

namespace Database\Factories;

use App\Enums\ApplicationPaymentEnum;
use App\Enums\UserStatusEnum;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role=Role::get()->pluck('id')->toArray();
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('###########'),
            'role_id' => $this->faker->randomElement($role),

//            'application_fee' => $this->faker->randomElement([ApplicationPaymentEnum::UNPAID,ApplicationPaymentEnum::PAID]),
            'status' => $this->faker->randomElement([UserStatusEnum::ACTIVE]),
//            'otp' => $this->faker->optional()->numerify('#######'),
            'is_verified' => $this->faker->boolean(70),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
