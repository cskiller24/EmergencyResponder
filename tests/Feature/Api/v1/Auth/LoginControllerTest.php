<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInvoke(): void
    {
        $user = User::factory()->user()->create(['password' => bcrypt('password')]);
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'token'
            ])
            ->assertJsonPath('email', $user->email);
    }

    public function testEmailDoesNotExistsError(): void
    {
        User::factory()->user()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => fake()->email(),
            'password' => 'password'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function testNoPasswordError(): void
    {
        User::factory()->user()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => fake()->email(),
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }
}
