<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $permissions = Permission::all();

        return view('admin.permissions', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated());

        \toastr()->success('Permission added successfully');

        return redirect()->route('permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        $permission->load('roles');

        return view('admin.permissions-show', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        \toastr()->success('Permission updated successfully');

        return redirect()->route('permissions.show', $permission->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        if($permission->hasAnyRole(Role::all())) {
            throw ValidationException::withMessages(['error' => 'The permission is being used']);
        }

        $permission->delete();

        \toastr()->success('Permission deleted successfully');

        return redirect()->route('permissions.index');
    }
}
