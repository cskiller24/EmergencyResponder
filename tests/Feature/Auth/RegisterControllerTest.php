<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testCreate(): void
    {
        $response = $this->get(route('register'));

        $response
            ->assertOk();
    }

    public function testStore(): void
    {
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'email', 'password']), ['password_confirmation' => $user->password]);

        $response = $this->post(route('register'), $data);

        $response
            ->assertRedirect();

        $assertData = [
            'model_type' => User::class,
            'model_id' => $user->id,
            'role_id' => Role::findByName('user')->id,
        ];

        $this->assertDatabaseHas($user->getTable(), $user->only(['name', 'email']));
        $dataUser = User::where('email', $user->email)->first();
        $assertData = [
            'model_type' => User::class,
            'model_id' => $dataUser->id,
            'role_id' => Role::findByName('user')->id,
        ];
        $this->assertDatabaseHas(config('permission.table_names.model_has_roles'), $assertData);
    }

    public function testDoesNotStoreWithoutName(): void
    {
        $this->withExceptionHandling();
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['email', 'password']), ['password_confirmation' => $user->password]);

        $response = $this->post(route('register'), $data);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();

        $this->assertDatabaseMissing($user->getTable(), $user->only(['name', 'email']));
    }

    public function testDoesNotStoreWithoutEmail(): void
    {
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'password']), ['password_confirmation' => $user->password]);

        $response = $this->post(route('register'), $data);

        $response
            ->assertSessionHasErrors('email')
            ->assertRedirect();

        $this->assertDatabaseMissing($user->getTable(), $user->only(['name', 'email']));
    }

    public function testDoesNotStoreWithoutPassword(): void
    {
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'email']), ['password_confirmation' => $user->password]);

        $response = $this->post(route('register'), $data);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect();

        $this->assertDatabaseMissing($user->getTable(), $user->only(['name', 'email']));
    }

    public function testDoesNotStoreIfPasswordNotSame(): void
    {
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'email', 'password']), ['password_confirmation' => fake()->password(8)]);

        $response = $this->post(route('register'), $data);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect();

        $this->assertDatabaseMissing($user->getTable(), $user->only(['name', 'email']));
    }
}
