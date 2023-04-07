<?php

namespace Tests\Feature\Admin;

use App\Mail\SendInvite;
use App\Models\Invite;
use App\Models\User;
use Mail;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class InviteControllerTest extends TestCase
{
    use UserCreator;

    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $this->createUserWithRole('admin');

        $invites = Invite::factory()->count(10)->create();
        $response = $this->get(route('admin.invites.index'));

        $response
            ->assertOk()
            ->assertSee($invites->first()->code);
    }

    public function testStore(): void
    {
        Mail::fake();

        $this->createUserWithRole('admin');

        $invite = Invite::factory()->existingRole()->make();
        /** @var array $data */
        $data = $invite->only(['email', 'role']);

        $response = $this->post(route('admin.invites.store'), $data);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($invite->getTable(), $data);
        Mail::assertSent(SendInvite::class);
    }

    public function testDoesNotStoreIfEmailExists(): void
    {
        Mail::fake();

        $this->createUserWithRole('admin');

        $user = User::factory()->user()->create();
        $invite = Invite::factory()->existingRole()->make($user->only(['email']));
        /** @var array $data */
        $data = $invite->only(['email', 'role']);

        $response = $this->post(route('admin.invites.store'), $data);

        $response
            ->assertSessionHasErrors('email')
            ->assertRedirect();

        $this->assertDatabaseMissing($invite->getTable(), $data);
        Mail::assertNothingSent();
    }

    public function testDoesNotStoreIfRoleMissing(): void
    {
        Mail::fake();

        $this->createUserWithRole('admin');

        $invite = Invite::factory()->make(['role' => fake()->word()]);
        /** @var array $data */
        $data = $invite->only(['email', 'role']);

        $response = $this->post(route('admin.invites.store'), $data);

        $response
            ->assertSessionHasErrors('role')
            ->assertRedirect();

        $this->assertDatabaseMissing($invite->getTable(), $data);
        Mail::assertNothingSent();
    }

    public function testAccept(): void
    {
        $invite = Invite::factory()->create();

        $response = $this->get(route('invites.accept', $invite->code));

        $response
            ->assertOk()
            ->assertSee($invite->email);
    }

    public function testProcess(): void
    {
        $this->createUserWithRole('admin');

        $invite = Invite::factory()->create();
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'password']), ['password_confirmation' => $user->password]);
        /** @var array $inviteEmail */
        $inviteEmail = $invite->only(['email']);

        $response = $this->post(route('invites.register', $invite->code), $data);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($user->getTable(), $inviteEmail);
        $this->assertDatabaseMissing($invite->getTable(), $inviteEmail);
    }

    public function testDoesNotProcessIfPasswordNotEqual(): void
    {
        $this->createUserWithRole('admin');

        $invite = Invite::factory()->create();
        $user = User::factory()->make();
        /** @var array $data */
        $data = array_merge($user->only(['name', 'password']), ['password_confirmation' => fake()->word()]);
        /** @var array $inviteEmail */
        $inviteEmail = $invite->only(['email']);

        $response = $this->post(route('invites.register', $invite->code), $data);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect();

        $this->assertDatabaseMissing($user->getTable(), $inviteEmail);
        $this->assertDatabaseHas($invite->getTable(), $inviteEmail);
    }

    public function testResend(): void
    {
        Mail::fake();

        $this->createUserWithRole('admin');

        $invite = Invite::factory()->existingRole()->create();

        $response = $this->post(route('admin.invites.resend', $invite->code));

        $response
            ->assertRedirect();

        Mail::assertSent(SendInvite::class);
    }

    public function testDestroy(): void
    {
        $this->createUserWithRole('admin');

        $invite = Invite::factory()->existingRole()->create();

        $response = $this->delete(route('admin.invites.destroy', $invite->code));

        $response
            ->assertRedirect();

        $this->assertDatabaseMissing($invite->getTable(), $invite->toArray());
    }
}
