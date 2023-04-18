<?php

use App\Enums\SubmissionStatusEnum;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

if (! function_exists('redirectRole')) {
    function redirectRole($user = null): string
    {
        if (! $user) {
            return RouteServiceProvider::HOME;
        }

        if (! in_array(HasRoles::class, class_uses_recursive($user::class))) {
            return RouteServiceProvider::HOME;
        }

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

if (! function_exists('parse_status')) {
    /**
     * Cast the submission status cases
     */
    function parse_status(int $status): string
    {
        return SubmissionStatusEnum::tryFrom($status)?->titleCase();
    }
}

if (! function_exists('validatePerPage')) {
    /**
     * Validate the per page get request in pagination
     */
    function validatePerPage(string|int $page = null, int $default = 10): int
    {
        if (! $page) {
            $page = request('p');
        }

        if (! is_numeric($page)) {
            return $default;
        }

        if (! in_array((int) $page, [10, 20, 30])) {
            return $default;
        }

        return $page;
    }
}

if (! function_exists('hasRole')) {
    /**
     * Check if user has role
     */
    function hasRole($role): bool
    {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}
