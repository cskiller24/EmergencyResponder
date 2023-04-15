<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class SettingsControllerTest extends TestCase
{
    use UserCreator;

    private function settingsEdit($role): void
    {
        $user = $this->createUserWithRole($role);

        $response = $this->get(route('settings.edit'));

        $response
            ->assertOk()
            ->assertSee($user->email)
            ->assertSee($user->name);
    }

    public function settingsUpdate($role): void
    {
        $this->createUserWithRole($role);

        $user = User::factory()->make();

        $response = $this->put(route('settings.update'), $user->only(['name','email']));

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($user->getTable(), $user->only(['name', 'email']));
    }

    public function editPassword($role): void
    {
        $this->createUserWithRole($role);

        $response = $this->get(route('settings.password.edit'));

        $response
            ->assertOk();
    }

    public function updatePassword($role): void
    {
        $this->createUserWithRole($role, ['password' => bcrypt('password')]);

        $response = $this->put(route('settings.password.update'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);

        $response
            ->assertRedirect();
    }

    public function testEditAccessInAllRoles(): void
    {
        $this->settingsEdit('user');
        $this->settingsEdit('moderator');
        $this->settingsEdit('admin');
    }

    public function testUpdateAccessInAllRoles(): void
    {
        $this->settingsUpdate('user');
        $this->settingsUpdate('moderator');
        $this->settingsUpdate('admin');
    }

    public function testPasswordEditAccessInAllRoles(): void
    {
        $this->editPassword('user');
        $this->editPassword('moderator');
        $this->editPassword('admin');
    }

    public function testPasswordUpdateAccessInAllRoles(): void
    {
        $this->updatePassword('user');
        $this->updatePassword('moderator');
        $this->updatePassword('admin');
    }
}
