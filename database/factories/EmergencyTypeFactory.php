<?php

namespace Database\Factories;

use App\Models\EmergencyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmergencyType>
 */
class EmergencyTypeFactory extends Factory
{
    protected $model = EmergencyType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->sentences(7, true),
        ];
    }
}
