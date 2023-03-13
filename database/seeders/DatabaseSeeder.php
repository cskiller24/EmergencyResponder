<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolesAndPermissionSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@eresponder.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $moderators = User::factory()->count(3)->create();
        $moderators->each(function ($moderator) {
            $moderator->assignRole('moderator');
        });

        $users = User::factory()->count(5)->create();
        $users->each(function ($user) {
            $user->assignRole('user');
        });

        $this->call([
            EmergencyTypeSeeder::class,
            ResponderSeeder::class,
            SubmissionSeeder::class,
        ]);
    }
}
