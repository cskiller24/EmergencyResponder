<?php

namespace Tests\Feature\Admin;

use App\Models\EmergencyType;
use App\Models\Responder;
use App\Models\Submission;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class EmergencyTypeControllerTest extends TestCase
{
    use UserCreator;

    public function testDisplayIndex(): void
    {
        $this->createUserWithRole('admin');

        $emergencyTypes = EmergencyType::factory()->count(3)->create();

        $response = $this->get(route('admin.emergency-types.index'));

        $response
            ->assertOk()
            ->assertSee($emergencyTypes->first()->name);
    }

    public function testStores(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->make();
        /** @var array $data */
        $data = $emergencyType->only(['name', 'description']);

        $response = $this->post(route('admin.emergency-types.store'), $data);

        $response
            ->assertStatus(302)
            ->assertRedirectToRoute('admin.emergency-types.index');

        $this->assertDatabaseHas($emergencyType->getTable(), $data);
    }

    public function testStoresWithoutDescription(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->make();
        /** @var array $data */
        $data = $emergencyType->only(['name']);

        $response = $this->post(route('admin.emergency-types.store'), $data);

        $response
            ->assertStatus(302)
            ->assertRedirectToRoute('admin.emergency-types.index');

        $this->assertDatabaseHas($emergencyType->getTable(), $data);
    }

    public function testDoesNotStoreWithoutName(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->make();
        /** @var array $data */
        $data = $emergencyType->only(['description']);

        $response = $this->post(route('admin.emergency-types.store'), $data);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();

        $this->assertDatabaseMissing($emergencyType->getTable(), $data);
    }

    public function testDisplayShow(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->create();

        $response = $this->get(route('admin.emergency-types.show', $emergencyType->id));

        $response
            ->assertOk()
            ->assertSeeText($emergencyType->name)
            ->assertSeeText($emergencyType->description);
    }

    public function testDisplayShowWithResponders(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()
            ->has(Responder::factory())
            ->create();

        $response = $this->get(route('admin.emergency-types.show', $emergencyType->id));

        $response
            ->assertOk()
            ->assertSeeText($emergencyType->name)
            ->assertSeeText($emergencyType->description)
            ->assertSeeText($emergencyType->responders->first()->name);
    }

    public function testDisplayShowWithSubmissions(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()
            ->has(Submission::factory())
            ->create();

        $response = $this->get(route('admin.emergency-types.show', $emergencyType->id));

        $response
            ->assertOk()
            ->assertSeeText($emergencyType->name)
            ->assertSeeText($emergencyType->description)
            ->assertSeeText($emergencyType->submissions->first()->name);
    }

    public function testUpdate(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->create();
        /** @var array $data */
        $data = EmergencyType::factory()->make()->only(['name', 'description']);

        $response = $this->put(route('admin.emergency-types.update', $emergencyType->id), $data);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.emergency-types.show', $emergencyType->id));

        $this->assertDatabaseHas($emergencyType->getTable(), $data);
    }

    public function testUpdateOnlyName(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->create();
        /** @var array $data */
        $data = EmergencyType::factory()->make()->only(['name']);

        $response = $this->put(route('admin.emergency-types.update', $emergencyType->id), $data);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.emergency-types.show', $emergencyType->id));

        $this->assertDatabaseHas($emergencyType->getTable(), $data);
    }

    public function testDoesNotUpdateWithoutName(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->create();
        /** @var array $data */
        $data = EmergencyType::factory()->make()->only(['description']);

        $response = $this->put(route('admin.emergency-types.update', $emergencyType->id), $data);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();
    }

    public function testDestroy(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()->create();

        $response = $this->delete(route('admin.emergency-types.destroy', $emergencyType->id));

        $response
            ->assertRedirect(route('admin.emergency-types.index'));

        $this->assertDatabaseMissing($emergencyType->getTable(), $emergencyType->toArray());
    }

    public function testDoesNotDestroyIfEmergencyTypeHasSubmission(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()
            ->has(Submission::factory())
            ->create();

        $response = $this->delete(route('admin.emergency-types.destroy', $emergencyType->id));

        $response
            ->assertSessionHasErrors('error')
            ->assertRedirect();

        $this->assertDatabaseHas($emergencyType->getTable(), $emergencyType->toArray());
    }

    public function testDoesNotDestroyIfEmergencyTypeHasResponder(): void
    {
        $this->createUserWithRole('admin');

        $emergencyType = EmergencyType::factory()
            ->has(Responder::factory())
            ->create();

        $response = $this->delete(route('admin.emergency-types.destroy', $emergencyType->id));

        $response
            ->assertSessionHasErrors('error')
            ->assertRedirect();

        $this->assertDatabaseHas($emergencyType->getTable(), $emergencyType->toArray());
    }
}
