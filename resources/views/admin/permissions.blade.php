@php
    $modalData = [
        'modalId' => 'createPermissionModal',
        'modalTitle' => 'Create Permission',
        'route' => route('permissions.store'),
        'putMethod' => false,
        'modalSaveButton' => 'Create',
    ]
@endphp

@extends('layouts.admin-home')

@section('title')
    Admin Permissions | Emergency Responser
@endsection

@section('page-title')
    Permissions
@endsection

@section('content')
    <div class="d-flex justify-content-between my-3">
        <p class="h4">List of permissions</p>
        <p>Number of permissions: <strong>{{ $permissions->count() }}</strong></p>
    </div>
    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createPermissionModal">Add permission</button>
    <div class="row row-cards">
        @forelse ($permissions as $permission)
            <div class="col-md-4">
                <div class="card border border-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>{{ str_title($permission->name) }}</div>
                        <a href="{{ route('permissions.show', $permission->id) }}" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        @empty
            <h2 class="text-center">No permissions available.</h2>
        @endforelse
    </div>

    @include('components.modals.roles-permissions-create', $modalData)
@endsection
