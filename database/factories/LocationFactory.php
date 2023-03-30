<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'locatable_id' => fake()->randomDigitNotZero(),
            'locatable_type' => \Illuminate\Support\Str::random(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'line' => fake()->streetAddress(),
            'zip' => mt_rand(1000, 7123),
            'region' => fake()->word(),
            'city' => fake()->city(),
            'country' => fake()->country(),
        ];
    }

    public function withModel(Model $model): self
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'locatable_id' => $model->getKey(),
                'locatable_type' => $model->getMorphClass(),
            ];
        });
    }
}
