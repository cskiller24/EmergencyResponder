@php
    $modalData = [
        'modalId' => 'updateRoleModal',
        'modalTitle' => 'Edit Role',
        'route' => route('admin.roles.update', $role->id),
        'putMethod' => true,
        'updateValue' => $role->name,
        'modalSaveButton' => 'Update',
    ];

    $modalDelete = [
        'modalId' => 'deleteRoleModal',
        'modalTitle' => 'Are you sure you want to delete ' . str_title($role->name) . ' role?',
        'route' => route('admin.roles.destroy', $role->id),
        'modalDeleteButton' => 'Delete role',
    ];
@endphp

@extends('layouts.admin-home')

@section('title')
    Role {{ str_title($role->name) }} | Emergency Responser
@endsection

@section('page-title')
    Roles
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3>{{ str_title($role->name) }}</h3>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <button class="btn btn-primary w-100 mb-1 mb-lg-0" data-bs-toggle="modal" data-bs-target="#updateRoleModal">
                Edit Role
            </button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-warning w-100 " data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                Delete Role
            </button>
        </div>
    </div>

    <form action="{{ route('admin.roles.permissions.store', $role->id) }}" method="post" >
        @csrf

        <input type="submit" class="btn btn-success w-100 mt-3" value="Save permissions">
        <div class="row row-cards mt-2">
            @foreach ($permissions as $permission)
                <div class="col-md-4">
                    <div class="card border border-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>{{ str_title($permission->name) }}</div>
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                @checked($role->permissions->contains('id', $permission->id))>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </form>

    @include('components.modals.delete', $modalDelete)
    @include('components.modals.roles-permissions-create', $modalData)
@endsection
