<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait UserCreator
{
    public function createUserWithRole(string $role = 'user', array $attributes = [], bool $authenticate = true, bool $isApi = false): User
    {
        $factoryMethod = match (strtolower($role)) {
            'admin' => 'admin',
            'moderator' => 'moderator',
            default => 'user',
        };

        return tap(User::factory()->{$factoryMethod}()->create($attributes), function ($user) use ($authenticate, $isApi) {
            if (! $authenticate) {
                return $user;
            }

            $isApi ? Sanctum::actingAs($user) : $this->actingAs($user);

            return $user;
        });
    }
}
