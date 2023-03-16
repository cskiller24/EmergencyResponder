@php
    $modalData = [
        'modalId' => 'createRoleModal',
        'modalTitle' => 'Create Role',
        'route' => route('roles.store'),
        'putMethod' => false,
        'modalSaveButton' => 'Create',
    ]
@endphp

@extends('layouts.admin-home')

@section('title')
    Admin Roles | Emergency Responser
@endsection

@section('page-title')
    Roles
@endsection

@section('content')
    <div class="d-flex justify-content-between my-3">
        <p class="h4">List of roles</p>
        <p>Number of roles: <strong>{{ $roles->count() }}</strong></p>
    </div>
    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">Add
        Role</button>
    <div class="row row-cards">
        @forelse ($roles as $role)
            <div class="col-md-4">
                <div class="card border border-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>{{ str_title($role->name) }}</div>
                        <a href="{{ route('roles.show', $role->id) }}" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        @empty
            <h2 class="text-center">No roles available.</h2>
        @endforelse
    </div>

    @include('components.modals.roles-permissions-create', $modalData)
@endsection
