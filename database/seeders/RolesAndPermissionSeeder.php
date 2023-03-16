<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = config('auth.defaults.guard');

        $listOfPermissions = [
            // Emergency Types
            'emergency_type_store',
            'emergency_type_update',
            'emergency_type_delete',
            // Reponders
            'responder_store',
            'responder_update',
            'responder_delete',
            // Locations
            'location_store',
            'location_update',
            'location_delete',
            // Related Links
            'related_link_store',
            'related_link_update',
            'related_link_delete',
            // Submission
            'submission_show',
            'submission_store',
            'submission_update',
            'submission_delete',

            // Users
            'user_show',
            'user_update',
            'user_delete',

            // Misc
            'invite_moderator',
            'approve_deny_submissions',
        ];

        $permissions = collect($listOfPermissions)->map(function ($permission) use ($guard) {
            return ['name' => $permission, 'guard_name' => $guard];
        });

        Permission::insert($permissions->toArray());

        $adminPermissions = [
            // Emergency Types
            'emergency_type_store',
            'emergency_type_update',
            'emergency_type_delete',
            // Reponders
            'responder_store',
            'responder_update',
            'responder_delete',
            // Locations
            'location_store',
            'location_update',
            'location_delete',
            // Related Links
            'related_link_store',
            'related_link_update',
            'related_link_delete',
            // Submission
            'submission_show',
            'submission_store',
            'submission_update',
            'submission_delete',

            'user_show',
            'invite_moderator',
            'approve_deny_submissions',
        ];

        $adminRole = Role::create(['name' => 'admin']);

        $adminRole->syncPermissions($adminPermissions);

        $moderatorPermissions = [
            'responder_update',
            'location_update',
            'related_link_update',
            'submission_show',
            'approve_deny_submissions',
            'related_link_update',
            'approve_deny_submissions',
            'user_show',
        ];

        $moderatorRole = Role::create(['name' => 'moderator']);

        $moderatorRole->syncPermissions($moderatorPermissions);

        $userPermissions = [
            'submission_show',
            'submission_store',
            'submission_update',
            'submission_delete',
            'user_show',
            'user_update',
            'user_delete',
        ];

        $userRole = Role::create(['name' => 'user']);

        $userRole->syncPermissions($userPermissions);
    }
}
