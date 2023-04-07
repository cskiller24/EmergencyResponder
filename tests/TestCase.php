<?php

namespace Tests;

use Database\Seeders\RolesAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected $seed = true;

    protected $seeder = RolesAndPermissionSeeder::class;
}
