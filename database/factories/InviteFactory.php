<?php

namespace Database\Factories;

use App\Models\Invite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;
use Symfony\Component\Uid\Ulid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invite>
 */
class InviteFactory extends Factory
{
    protected $model = Invite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => Ulid::generate(),
            'email' => fake()->safeEmail(),
            'role' => 'moderator',
        ];
    }

    public function existingRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::all()->random(1)->first()->name,
        ]);
    }
}
