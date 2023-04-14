<?php

namespace Tests\Feature\User;

use App\Enums\SubmissionStatusEnum;
use App\Models\Contact;
use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Submission;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class SubmissionControllerTest extends TestCase
{
    use UserCreator;

    /**
     * A basic feature test example.
     */
    public function testStore(): void
    {
        $this->createUserWithRole('user');

        $submission = Submission::factory()->make();
        $emergencyType = EmergencyType::factory()->create();
        $contacts = Contact::factory()->count(3)->make();
        $relatedLinks = RelatedLink::factory()->count(2)->make();
        $location = Location::factory()->make();

        $response = $this->post(route('public.submissions.store'), [
            'submitter_notify' => $submission->submitter_notify,
            'emergency_type_id' => $emergencyType->id,
            'status' => $submission->status->value,
            'name' => $submission->name,
            'description' => $submission->description,
            'links' => $relatedLinks->toArray(),
            'contacts' => $contacts->toArray(),
            'longitude' => $location->longitude,
            'latitude' => $location->latitude,
            'city' => $location->city,
            'region' => $location->region,
            'country' => $location->country,
            'zip' => $location->zip,
            'line' => $location->line,
        ]);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($submission->getTable(), $submission->only(['name', 'description']));
        $this->assertDatabaseHas($emergencyType->getTable(), $emergencyType->only('name'));
        $this->assertDatabaseHas($location->getTable(), $location->only(['longitude', 'latitude', 'city', 'region', 'zip', 'line']));

        $contacts->each(function (Contact $contact) use ($submission) {
            $this->assertDatabaseHas($contact->getTable(), [
                'contactable_type' => $submission::class,
                'type' => $contact->type,
                'detail' => $contact->detail,
            ]);
        });

        $relatedLinks->each(function (RelatedLink $relatedLink) use ($submission) {
            $this->assertDatabaseHas($relatedLink->getTable(), [
                'related_linkable_type' => $submission::class,
                'link' => $relatedLink->link,
            ]);
        });
    }

    public function testUpdate(): void
    {
        $user = $this->createUserWithRole('user');

        $submission = Submission::factory()
                ->has(Location::factory())
                ->has(RelatedLink::factory()->count(3))
                ->has(EmergencyType::factory())
                ->has(Contact::factory()->count(3))
                ->create(['status' => SubmissionStatusEnum::SUBMITTED->value, 'submitted_by' => $user->id]);
        $emergencyType = $submission->emergencyType;
        $contacts = $submission->contacts;
        $relatedLinks = RelatedLink::factory()->count(2)->make();
        $location = Location::factory()->make();

        $response = $this->put(route('public.submissions.update', $submission->id), [
            'submitter_notify' => $submission->submitter_notify,
            'emergency_type_id' => $emergencyType->id,
            'status' => $submission->status->value,
            'name' => $submission->name,
            'description' => $submission->description,
            'links' => $relatedLinks->toArray(),
            'contacts' => $contacts->toArray(),
            'longitude' => $location->longitude,
            'latitude' => $location->latitude,
            'city' => $location->city,
            'region' => $location->region,
            'country' => $location->country,
            'zip' => $location->zip,
            'line' => $location->line,
        ]);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($submission->getTable(), $submission->only(['name', 'description']));
        $this->assertDatabaseHas($emergencyType->getTable(), $emergencyType->only('name'));
        $this->assertDatabaseHas($location->getTable(), $location->only(['longitude', 'latitude', 'city', 'region', 'zip', 'line']));

        $contacts->each(function (Contact $contact) use ($submission) {
            $this->assertDatabaseHas($contact->getTable(), [
                'contactable_type' => $submission::class,
                'type' => $contact->type,
                'detail' => $contact->detail,
            ]);
        });

        $relatedLinks->each(function (RelatedLink $relatedLink) use ($submission) {
            $this->assertDatabaseHas($relatedLink->getTable(), [
                'related_linkable_type' => $submission::class,
                'link' => $relatedLink->link,
            ]);
        });
    }
}
