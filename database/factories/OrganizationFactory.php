<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Organization;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
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
        $faker = FakerFactory::create('ru_RU');

        return [
            'name' => $faker->company,
            'phone_numbers' => json_encode([$this->faker->phoneNumber, $this->faker->phoneNumber]),
            'building_id' => Building::factory(),
        ];
    }
}
