<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;

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

if (! function_exists('str_title')) {
    /**
     * Turn snake case into title case
     */
    function str_title(string $string): string
    {
        return Str::title(str_replace('_', ' ', $string));
    }
}

if (! function_exists('str_snake')) {
    /**
     * Turn string into snake case
     */
    function str_snake(string $string): string
    {
        return Str::snake($string);
    }
}
