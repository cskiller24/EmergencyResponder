<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Tests\Traits\UserCreator;

class AdminControllerTest extends TestCase
{
    use UserCreator;

    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $this->createUserWithRole('admin');

        $response = $this->get(route('admin.index'));

        $response
            ->assertOk();
    }
}
