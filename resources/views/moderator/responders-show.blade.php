@extends('layouts.moderator-home')

@section('title')
{{ $responder->name }} | Emergency Responder
@endsection

@section('page-title')
Responder
@endsection

@section('content')
<div class="card border-0 rounded-0">
    <div class="card-body">
        <div class="card-title">
            <h2>Responder</h2>
        </div>
        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-title">{{ $responder->name }}</div>
                        </div>
                        <p class="card-text">{{ $responder->description }}</p>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Location</p>
                        <p>{{ $responder->location->fullAddress() }}</p>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Emergency Type</p>
                        <p>{{ $responder->emergencyType->name }}</p>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Links</p>
                        <ul class="list-group list-group-numbered">
                            @forelse ($responder->relatedLinks as $relatedLink)
                            <li class="list-group-item">{{ $relatedLink->link }}</li>
                            @empty
                            <p class="h5">Empty Links</p>
                            @endforelse
                        </ul>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Contacts</p>
                        <ul class="list-group list-group-numbered">
                            @forelse ($responder->contacts as $contact)
                            <li class="list-group-item">{{ str_title($contact->type) }} - <b>{{ $contact->detail }}</b></li>
                            @empty
                            <p class="h5">Empty Contacts</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('moderator.responders.edit', $responder->id) }}" class="btn btn-success w-100 btn-block">
            Update responder
        </a>
    </div>
</div>
@endsection
