@php
    $modalData = [
        'modalId' => 'updateEmergencyTypeModal',
        'modalTitle' => 'Edit Emergency Type',
        'route' => route('admin.emergency-types.update', $emergencyType->id),
        'putMethod' => true,
        'nameValue' => $emergencyType->name,
        'descriptionValue' => $emergencyType->description,
        'modalSaveButton' => 'Update',
    ];

    $modalDelete = [
        'modalId' => 'deleteEmergencyTypeModal',
        'modalTitle' => 'Are you sure you want to delete ' . $emergencyType->name . ' emergency type?',
        'route' => route('admin.emergency-types.destroy', $emergencyType->id),
        'modalDeleteButton' => 'Delete emergency type',
    ];
@endphp

@extends('layouts.admin-home')

@section('title')
    Emergency Type {{ $emergencyType->name }} | Emergency Responser
@endsection

@section('page-title')
    Emergency Type
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3>{{ $emergencyType->name }}</h3>
            <h4>{{ $emergencyType->description }}</h4>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <button class="btn btn-primary w-100 mb-1 mb-lg-0" data-bs-toggle="modal"
                data-bs-target="#updateEmergencyTypeModal">
                Edit emergency type
            </button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-warning w-100 " data-bs-toggle="modal" data-bs-target="#deleteEmergencyTypeModal">
                Delete emergency type
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between">
                <div class="h4">Lists of emergency type used</div>
                <div class="h4">Number of emergency type used:
                    {{ $emergencyType->responders->count() + $emergencyType->submissions->count() }} </div>
            </div>
            <div class="card-text">
                <div class="hr-text">Responders</div>
                <ul>
                    @forelse ($emergencyType->responders as $responder)
                        <li>
                            {{ $responder->name }}
                        </li>
                    @empty
                        There are no responders
                    @endforelse
                </ul>
                <div class="hr-text">End of responders</div>
                <div class="hr-text">Submissions</div>
                <ul>
                    @forelse ($emergencyType->submissions as $submission)
                        <li>
                            {{ $submission->name }}
                        </li>
                    @empty
                        There are no submissions
                    @endforelse
                </ul>
                <div class="hr-text">End of submissions</div>

            </div>
        </div>
    </div>

    @include('components.modals.delete', $modalDelete)
    @include('components.modals.emergency-types', $modalData)
@endsection
