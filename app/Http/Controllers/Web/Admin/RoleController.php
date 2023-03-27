<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermissionRequest;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = Role::all();

        return view('admin.roles', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        Role::create($request->validated());

        \toastr()->success('Role added successfully');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $role->load('permissions');

        $permissions = Permission::all();

        return view('admin.roles-show', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        \toastr()->success('Role updated successfully');

        return redirect()->route('admin.roles.show', $role->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->hasAnyPermission(Permission::all())) {
            throw ValidationException::withMessages(['error' => 'The role is being use']);
        }

        $role->delete();

        \toastr()->success('Deleted role successfully');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Store permissions in roles
     */
    public function storePermissions(RolePermissionRequest $request, Role $role): RedirectResponse
    {
        $role->syncPermissions($request->permissions);

        \toastr()->success('Role permissions update successfully');

        return redirect()->back();
    }
}
