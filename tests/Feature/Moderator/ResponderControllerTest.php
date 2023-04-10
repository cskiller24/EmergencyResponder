<?php

namespace Tests\Feature\Moderator;

use App\Models\Contact;
use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class ResponderControllerTest extends TestCase
{
    use UserCreator;
    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRole('moderator');
    }

    public function testIndex(): void
    {
        $responders = Responder::factory()->count(mt_rand(1, 10))
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('moderator.responders.index'));

        $response
            ->assertOk()
            ->assertSee($responders->random()->first()->name);
    }

    public function testIndexSearch(): void
    {
        $responder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create([
                'name' => 'ZZZZZZ',
                'updated_at' => now()->subDay()
            ]);

        Responder::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('moderator.responders.index', ['s' => 'ZZZZZZ']));

        $response
            ->assertOk()
            ->assertSee($responder->name);
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
                'updated_at' => now()->subDay()
            ]);

        Responder::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('moderator.responders.index', [
            'f' => 'nearest',
            '_latitude' => 14.60683464626731,
            '_longitude' => 121.00068168968626
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
                'updated_at' => now()->subDay()
            ]);

        Responder::factory()
            ->count(20)
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();


        $response = $this->get(route('moderator.responders.index', [
            'f' => 'farthest',
            '_latitude' => -14.632111,
            '_longitude' =>  -58.987749
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

        $response = $this->get(route('moderator.responders.show', $responder->id));

        $response
            ->assertOk()
            ->assertSeeText($responder->name)
            ->assertSeeText($responder->location->city)
            ->assertSeeText($responder->relatedLinks->random()->first()->link)
            ->assertSeeText($responder->emergencyType->name)
            ->assertSeeText($responder->contacts->random()->first()->detail);
    }

    public function testEdit(): void
    {
        $responder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $response = $this->get(route('moderator.responders.edit', $responder->id));

        $response
            ->assertOk()
            ->assertSee($responder->name)
            ->assertSee($responder->location->city)
            ->assertSee($responder->relatedLinks->random()->first()->link)
            ->assertSee($responder->emergencyType->name)
            ->assertSee($responder->contacts->random()->first()->detail);
    }

    public function testUpdate(): void
    {
        $this->withExceptionHandling();

        $responder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();

        $emergencyType = EmergencyType::factory()->create();
        $newResponder = Responder::factory()
            ->has(Location::factory())
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->make();

        $link1 = fake()->url();
        $link2 = fake()->url();

        $response = $this->put(route('moderator.responders.update', $responder->id),[
            'emergency_type_id' => $emergencyType->id,
            'name' => $newResponder->name,
            'description' => $newResponder->description,
            'status' => $newResponder->status->value,
            'longitude' => $responder->longitude,
            'latitude' => $responder->latitude,

            'city' => 'Manila',
            'region' => 'NCR',
            'country' => 'Philippines',
            'line' => 'Test Line',
            'zip' => 1009,

            'contacts' => [
                ['type' => 'email', 'detail' => 'fake@email.com'],
                ['type' => 'email', 'detail' => 'fake@email2.com']
            ],
            'links' => [
                ['link' => $link1],
                ['link' => $link2],
            ],
        ]);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($responder->getTable(), [
            'name' => $newResponder->name,
            'description' => $newResponder->description,
            'status' => $newResponder->status,
            'latitude' => $responder->latitude,
            'longitude' => $responder->longitude,
            'emergency_type_id' => $emergencyType->id
        ]);

        $this->assertDatabaseHas('contacts', [
            'contactable_type' => Responder::class,
            'contactable_id' => $responder->id,
            'detail' => 'fake@email.com'
        ]);

        $this->assertDatabaseHas('contacts', [
            'contactable_type' => Responder::class,
            'contactable_id' => $responder->id,
            'detail' => 'fake@email2.com'
        ]);

        $this->assertDatabaseHas('locations', [
            'locatable_type' => Responder::class,
            'locatable_id' => $responder->id,
            'city' => 'Manila',
            'region' => 'NCR',
            'country' => 'Philippines',
            'line' => 'Test Line',
            'zip' => 1009,
            'longitude' => $responder->longitude,
            'latitude' => $responder->latitude,
        ]);

        $this->assertDatabaseHas('related_links', [
            'related_linkable_type' => Responder::class,
            'related_linkable_id' => $responder->id,
            'link' => $link1,
        ]);

        $this->assertDatabaseHas('related_links', [
            'related_linkable_type' => Responder::class,
            'related_linkable_id' => $responder->id,
            'link' => $link2,
        ]);
    }
}
