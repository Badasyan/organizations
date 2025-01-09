<?php

namespace Database\Factories;

use App\Models\Building;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Building>
 */
class BuildingFactory extends Factory
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
            'address' => $faker->address,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
        ];
    }
}
