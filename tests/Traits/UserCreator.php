<?php

namespace Tests\Traits;

use App\Models\User;

trait UserCreator
{
    public function createUserWithRole(string $role = 'user', array $attributes = [], bool $authenticate = true): User
    {
        $factoryMethod = match (strtolower($role)) {
            'admin' => 'admin',
            'moderator' => 'moderator',
            default => 'user',
        };

        return tap(
            User::factory()->{$factoryMethod}()->create($attributes),
            fn ($user) => $authenticate && $this->actingAs($user)
        );
    }
}
