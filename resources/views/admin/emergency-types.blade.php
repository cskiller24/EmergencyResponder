@php
    $createModal = [
        'modalId' => 'createEmergencyTypeModal',
        'modalTitle' => 'Create Emergency Type',
        'route' => route('admin.emergency-types.store'),
        'modalSaveButton' => 'Create'
    ]
@endphp

@extends('layouts.admin-home')

@section('title')
    Admin Emergency Types | Emergency Responser
@endsection

@section('page-title')
    Emergency Types
@endsection

@section('content')
<div class="d-flex justify-content-between my-3">
    <p class="h4">List of Emergency Types</p>
    <p>Number of emergency types: <strong>{{ $emergencyTypes->count() }}</strong></p>
</div>
<button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createEmergencyTypeModal">Add emergency type</button>
<div class="row row-cards">
    @forelse ($emergencyTypes as $emergencyType)
        <div class="col-md-4">
            <div class="card border border-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>{{ $emergencyType->name }}</div>
                    <a href="{{ route('admin.emergency-types.show', $emergencyType->id) }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
    @empty
        <h2 class="text-center">No emergency types available.</h2>
    @endforelse
</div>

@include('components.modals.emergency-types', $createModal)
@endsection

