@php
    $modalData = [
        'modalId' => 'updatePermissionModal',
        'modalTitle' => 'Edit Permission',
        'route' => route('admin.permissions.update', $permission->id),
        'putMethod' => true,
        'updateValue' => $permission->name,
        'modalSaveButton' => 'Update',
    ];

    $modalDelete = [
        'modalId' => 'deletePermissionModal',
        'modalTitle' => 'Are you sure you want to delete '.str_title($permission->name).' permission?',
        'route' => route('admin.permissions.destroy', $permission->id),
        'modalDeleteButton' => 'Delete permission'
    ]
@endphp

@extends('layouts.admin-home')

@section('title')
    Permission {{ str_title($permission->name) }} | Emergency Responser
@endsection

@section('page-title')
    Permissions
@endsection

@section('content')
    {{-- <div class="d-flex justify-content-between my-3">
        <p class="h4">List of permissions</p>
        <p>Number of permissions: <strong>{{ $permissions->count() }}</strong></p>
    </div> --}}
    <div class="card">
        <div class="card-body">
            <h3>{{ str_title($permission->name) }}</h3>

            <h4>Used by: </h4>
            <ul>
                @forelse ($permission->roles as $role)
                    <li>{{ str_title($role->name) }}</li>
                @empty
            </ul>
                <p>No role being used</p>
            @endforelse
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <button class="btn btn-primary w-100 mb-1 mb-lg-0" data-bs-toggle="modal" data-bs-target="#updatePermissionModal">
                Edit Permission
            </button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-warning w-100 " data-bs-toggle="modal" data-bs-target="#deletePermissionModal">
                Delete Permission
            </button>
        </div>
    </div>

    @include('components.modals.delete', $modalDelete)
    @include('components.modals.roles-permissions-create', $modalData)
@endsection
