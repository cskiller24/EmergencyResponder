<?php

namespace Tests\Feature\Public;

use App\Models\Contact;
use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
use Tests\TestCase;

class ResponderControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $responders = Responder::factory()
            ->count(10)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.responders.index'));

        $response
            ->assertOk()
            ->assertSeeText($responders->random()->first()->name);
    }

    public function testIndexSearch(): void
    {
        Responder::factory()
            ->count(10)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $responder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create(['name' => 'AAAA']);

        $response = $this->get(route('public.responders.index', ['s' => 'AAAA']));

        $response
            ->assertOk()
            ->assertSeeText($responder->name);
    }

    public function testIndexNearest(): void
    {
        $responder = Responder::factory()
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

        Responder::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.responders.index', [
            'f' => 'nearest',
            '_latitude' => 14.60683464626731,
            '_longitude' => 121.00068168968626,
        ]));

        $response
            ->assertOk()
            ->assertSeeText($responder->name);
    }

    public function testIndexFarthest(): void
    {
        $responder = Responder::factory()
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

        Responder::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.responders.index', [
            'f' => 'farthest',
            '_latitude' => -14.632111,
            '_longitude' => -58.987749,
        ]));

        $response
            ->assertOk()
            ->assertSeeText($responder->name);
    }

    public function testShow(): void
    {
        $responder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('public.responders.show', $responder->id));

        $response
            ->assertOk()
            ->assertSeeText($responder->name)
            ->assertSeeText($responder->location->city)
            ->assertSeeText($responder->relatedLinks->random()->first()->link)
            ->assertSeeText($responder->emergencyType->name)
            ->assertSeeText($responder->contacts->random()->first()->detail);
    }
}
