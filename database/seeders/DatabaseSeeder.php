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
        $this->call([
            RolesAndPermissionSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@eresponder.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@eresponder.com',
            'password' => bcrypt('password')
        ])->assignRole('moderator');

        $moderators = User::factory()->count(3)->create();
        $moderators->each(function ($moderator) {
            $moderator->assignRole('moderator');
        });

        $users = User::factory()->count(100)->create();
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
