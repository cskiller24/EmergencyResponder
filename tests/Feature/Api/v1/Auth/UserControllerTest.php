<?php

namespace Tests\Feature\Api\v1\Auth;

use Tests\TestCase;
use Tests\Traits\UserCreator;

class UserControllerTest extends TestCase
{
    use UserCreator;

    public function testDisplayUserInformation(): void
    {
        $user = $this->createUserWithRole('user', isApi: true);

        $response = $this->getJson('/api/v1/user');

        $response
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }
}
