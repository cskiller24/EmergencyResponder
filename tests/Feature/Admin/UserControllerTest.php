<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class UserControllerTest extends TestCase
{
    use UserCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRole('admin');
    }

    public function testIndex(): void
    {
        $moderator = User::factory()->moderator()->create();
        $user = User::factory()->user()->create();

        $response = $this->get(route('admin.users.index'));

        $response
            ->assertOk()
            ->assertSeeText($moderator->name)
            ->assertSeeText($user->name);
    }

    public function testShow(): void
    {
        $user = User::factory()->user()->create();

        $response = $this->get(route('admin.users.index', $user->id));

        $response
            ->assertOk()
            ->assertSeeText($user->name)
            ->assertSeeText(str_title($user->getRoleNames()->first()));
    }
}
