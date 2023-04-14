<?php

namespace Tests\Feature\Public;

use App\Models\Contact;
use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubmissionControllerTest extends TestCase
{
     /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $submissions = Submission::factory()
            ->count(10)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.submissions.index'));

        $response
            ->assertOk()
            ->assertSeeText($submissions->random()->first()->name);
    }

    public function testIndexSearch(): void
    {
        Submission::factory()
            ->count(10)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $submission = Submission::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create(['name' => 'AAAA']);

        $response = $this->get(route('public.submissions.index', ['s' => 'AAAA']));

        $response
            ->assertOk()
            ->assertSeeText($submission->name);
    }

    public function testIndexNearest(): void
    {
        $submission = Submission::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create([
                'name' => 'NEAREST ME',
                'latitude' => 14.59615839236007,
                'longitude' => 121.00406021183342,
                'updated_at' => now()->subDay(),
            ]);

        Submission::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.submissions.index', [
            'f' => 'nearest',
            '_latitude' => 14.60683464626731,
            '_longitude' => 121.00068168968626,
        ]));

        $response
            ->assertOk()
            ->assertSeeText($submission->name);
    }

    public function testIndexFarthest(): void
    {
        $submission = Submission::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create([
                'name' => 'FARTHEST ME',
                'latitude' => 14.59615839236007,
                'longitude' => 121.00406021183342,
                'updated_at' => now()->subDay(),
            ]);

        Submission::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.submissions.index', [
            'f' => 'farthest',
            '_latitude' => -14.632111,
            '_longitude' => -58.987749,
        ]));

        $response
            ->assertOk()
            ->assertSeeText($submission->name);
    }

    public function testShow(): void
    {
        $submission = Submission::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.submissions.show', $submission->id));

        $response
            ->assertOk()
            ->assertSeeText($submission->name)
            ->assertSeeText($submission->location->city)
            ->assertSeeText($submission->relatedLinks->random()->first()->link)
            ->assertSeeText($submission->emergencyType->name)
            ->assertSeeText($submission->contacts->random()->first()->detail);
    }
}
