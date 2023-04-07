<?php

namespace Tests\Feature\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class PermissionControllerTest extends TestCase
{
    use UserCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRole('admin');
    }

    public function testIndex(): void
    {
        $permission = Permission::create(['name' => fake()->word()]);

        $response = $this->get(route('admin.permissions.index'));

        $response
            ->assertOk()
            ->assertSeeText(str_title($permission->name));
    }

    public function testStore(): void
    {
        $data = ['name' => fake()->word()];

        $response = $this->post(route('admin.permissions.store'), $data);

        $response
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseHas('permissions', ['name' => str_snake($data['name'])]);
    }

    public function testDoesNotStoreWithoutName(): void
    {
        $response = $this->post(route('admin.permissions.store'), []);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();
    }

    public function testShow(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->name())]);

        $response = $this->get(route('admin.permissions.show', $permission->id));

        $response
            ->assertOk()
            ->assertSeeText(str_title($permission->name));
    }

    public function testShowWithRole(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->word())]);
        $role = Role::create(['name' => fake()->word()]);
        $permission->assignRole($role);

        $response = $this->get(route('admin.permissions.show', $permission->id));

        $response
            ->assertOk()
            ->assertSeeText(str_title($permission->name))
            ->assertSeeText(str_title($role->name));
    }

    public function testUpdate(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->word())]);
        $data = ['name' => fake()->word()];

        $response = $this->put(route('admin.permissions.update', $permission->id), $data);

        $response
            ->assertRedirect(route('admin.permissions.show', $permission->id));

        $this->assertDatabaseHas($permission->getTable(), ['name' => str_snake($data['name'])]);
    }

    public function testDoesNotUpdateWithoutName(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->word())]);

        $response = $this->put(route('admin.permissions.update', $permission->id));

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();
    }

    public function testDestroy(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->word())]);

        $response = $this->delete(route('admin.permissions.destroy', $permission->id));

        $response
            ->assertRedirect(route('admin.permissions.index'));
    }

    public function testDoesNotDestroyIfPermissionHasRole(): void
    {
        $permission = Permission::create(['name' => str_snake(fake()->word())]);
        $role = Role::create(['name' => fake()->word()]);
        $permission->assignRole($role);

        $response = $this->delete(route('admin.permissions.destroy', $permission->id));

        $response
            ->assertSessionHasErrors(['error' => 'The permission is being used'])
            ->assertRedirect();

        $this->assertDatabaseHas($permission->getTable(), ['name' => $permission->name]);
    }
}
