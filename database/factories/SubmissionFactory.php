<?php

namespace Database\Factories;

use App\Models\EmergencyType;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'submitted_by' => User::factory(),
            'submitter_notify' => (bool) mt_rand(0, 1),
            'status' => mt_rand(1, 4), // to update
            'name' => fake()->sentence(),
            'description' => fake()->sentences(3, true),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'monitored_by' => User::factory(),
            'emergency_type_id' => EmergencyType::factory(),
        ];
    }
}
