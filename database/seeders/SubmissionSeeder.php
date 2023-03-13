<?php

namespace Database\Seeders;

use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('user')->get();
        $moderators = User::role('moderator')->get();

        $users->each(function (User $user) use ($moderators) {
            // If the user is monitored
            $submission = Submission::factory()
                ->has(Location::factory())
                ->has(RelatedLink::factory())
                ->has(EmergencyType::factory());

            if (mt_rand(0, 1) === 1) {
                $moderatorsId = $moderators->random()->take(1)->pluck('id')[0];
                $submission->create([
                    'monitored_by' => $moderatorsId,
                    'submitter_email' => $user->email,
                ]);
            } else {
                $submission->create([
                    'submitter_email' => $user->email,
                ]);
            }
        });
    }
}
