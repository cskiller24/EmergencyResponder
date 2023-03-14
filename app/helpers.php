<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;

if (! function_exists('redirectRole')) {
    function redirectRole(User $user): string
    {
        if ($user->hasRole('admin')) {
            return RouteServiceProvider::ADMIN;
        }

        if ($user->hasRole('moderator')) {
            return RouteServiceProvider::MODERATOR;
        }

        if ($user->hasRole('user')) {
            return RouteServiceProvider::USER;
        }

        return '/';
    }
}

if (! function_exists('route_named')) {
    function route_named(string $path): bool
    {
        return request()->route()->named($path);
    }
}
