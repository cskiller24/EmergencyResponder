<?php

namespace Tests\Feature\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\UserCreator;

class RoleControllerTest extends TestCase
{
    use UserCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRole('admin');
    }

    public function testIndex(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);

        $response = $this->get(route('admin.roles.index'));

        $response
            ->assertOk()
            ->assertSeeText(str_title($role->name));
    }

    public function testStore(): void
    {
        $data = ['name' => fake()->word()];

        $response = $this->post(route('admin.roles.store'), $data);

        $response
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseHas('roles', ['name' => str_snake($data['name'])]);
    }

    public function testDoesNotStoreWithoutName(): void
    {
        $response = $this->post(route('admin.roles.store'));

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();
    }

    public function testShow(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);

        $response = $this->get(route('admin.roles.show', $role->id));

        $response
            ->assertOk()
            ->assertSeeText(str_title($role->name));
    }

    public function testShowWithPermission(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);
        $permission = Permission::create(['name' => str_snake(fake()->word())]);

        $response = $this->get(route('admin.roles.show', $role->id));

        $response
            ->assertOk()
            ->assertSeeText(str_title($role->name))
            ->assertSeeText(str_title($permission->name));
    }

    public function testUpdate(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);
        $data = ['name' => fake()->word()];

        $response = $this->put(route('admin.roles.update', $role->id), $data);

        $response
            ->assertRedirect();

        $this->assertDatabaseHas($role->getTable(), ['name' => str_snake($data['name'])]);
        $this->assertDatabaseMissing($role->getTable(), $role->only('name'));
    }

    public function testDoesNotUpdateWithoutName(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);

        $response = $this->put(route('admin.roles.update', $role->id));

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();

        $this->assertDatabaseHas($role->getTable(), $role->only('name'));
    }

    public function testDestroy(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);

        $response = $this->delete(route('admin.roles.destroy', $role->id));

        $response
            ->assertRedirect();

        $this->assertDatabaseMissing($role->getTable(), $role->only(['name']));
    }

    public function testDoestNotDestroyIfRoleHasPermission(): void
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);
        $permission = Permission::create(['name' => str_snake(fake()->word())]);
        $role->givePermissionTo($permission);

        $response = $this->delete(route('admin.roles.destroy', $role->id));

        $response
            ->assertSessionHasErrors(['error' => 'The role is being use'])
            ->assertRedirect();

        $this->assertDatabaseHas($role->getTable(), $role->only(['name']));
    }

    public function testStorePermissions()
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);
        $permission = Permission::create(['name' => str_snake(fake()->word())]);

        $data = ['permissions' => [$permission->name]];

        $response = $this->post(route('admin.roles.permissions.store', $role->id), $data);

        $response
            ->assertRedirect();

        $dataAssert = [
            config('permission.column_names.role_pivot_key') ?? 'role_id' => $role->id,
            config('permission.column_names.permission_pivot_key') ?? 'permission_id' => $permission->id,
        ];

        $this->assertDatabaseHas(config('permission.table_names.role_has_permissions'), $dataAssert);
    }

    public function testDoesNotStorePermissionsIfPermissionDoesNotExists()
    {
        $role = Role::create(['name' => str_snake(fake()->word())]);

        $data = ['permissions' => [fake()->word()]];

        $response = $this->post(route('admin.roles.permissions.store', $role->id), $data);

        $response
            ->assertSessionHasErrors(['permissions.0' => 'Permission does not exists'])
            ->assertRedirect();
    }
}
