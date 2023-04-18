<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testInvoke(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Created user successfully');

        $this->assertDatabaseHas($user->getTable(), $user->only(['email']));
    }

    public function testDoesNotRegisterIfNoName(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/register', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseMissing($user->getTable(), $user->only(['email']));
    }

    public function testDoesNotRegisterIfNoEmail(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/register', [
            'name' => $user->name,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseMissing($user->getTable(), $user->only(['email']));
    }

    public function testDoeNotRegsiterIfNoPassword(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/register', [
            'name' => fake()->name(),
            'email' => $user->email,
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');

        $this->assertDatabaseMissing($user->getTable(), $user->only(['email']));
    }

    public function testDoesNotRegisterIfEmailExists(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/register', [
            'name' => fake()->name(),
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function testDoesNotRegisterIfPasswordAreDifferent()
    {
        $user = User::factory()->make();
        $response = $this->postJson('/api/v1/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password2'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');

        $this->assertDatabaseMissing($user->getTable(), $user->only(['email']));
    }
}
