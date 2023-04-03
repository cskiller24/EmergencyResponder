<?php

namespace Database\Seeders;

use App\Models\Contact;
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
        $users = User::role('user')->pluck('id');
        $moderators = User::role('moderator')->pluck('id');

        $users->each(function ($userId) use ($moderators) {
            // If the user is monitored
            $submission = Submission::factory()
                ->has(Location::factory())
                ->has(RelatedLink::factory()->count(mt_rand(1, 3)))
                ->has(EmergencyType::factory())
                ->has(Contact::factory()->count(mt_rand(1, 3)));

            if (mt_rand(0, 1) === 1) {
                $submission->create([
                    'monitored_by' => $moderators->random(1)->first(),
                    'submitted_by' => $userId,
                ]);
            } else {
                $submission->create([
                    'submitted_by' => $userId,
                    'monitored_by' => null
                ]);
            }
        });
    }
}
