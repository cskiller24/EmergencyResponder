<?php

namespace Database\Factories;

use App\Models\EmergencyType;
use App\Models\Responder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Responder>
 */
class ResponderFactory extends Factory
{
    protected $model = Responder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->sentences(3, true),
            'status' => rand(1, 3), // to update
            'emergency_type_id' => EmergencyType::factory(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
