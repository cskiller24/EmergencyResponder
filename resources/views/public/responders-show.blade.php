@extends('layouts.user-home')

@section('title')
    {{ $responder->name }} Responder | Emergency Responder
@endsection

@section('page-title')
    {{ $responder->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Description: <strong>{{ $responder->description }}</strong></p>
            <p>Emergency Type: <strong>{{ $responder->emergencyType->name }}</strong></p>
            <div class="hr-text my-1">Location</div>
            <div class="row">
                <p class="col-6">
                    Latitude: <strong>{{ $responder->location->latitude }}</strong>
                </p>
                <p class="col-6">
                    Longitude: <strong>{{ $responder->location->latitude }}</strong>
                </p>
                <p class="col-md-6">
                    Line: <strong>{{ $responder->location->line }}</strong>
                </p>
                <p class="col-md-6">
                    City: <strong>{{ $responder->location->city }}</strong>
                </p>
                <p class="col-md-6">
                    Zip: <strong>{{ $responder->location->zip }}</strong>
                </p>
                <p class="col-md-6">
                    Region: <strong>{{ $responder->location->region }}</strong>
                </p>
                <p class="col-md-6">
                    Country: <strong>{{ $responder->location->country }}</strong>
                </p>
            </div>
            <div class="hr-text my-1">Contacts</div>
            @forelse ($responder->contacts as $contact)
                <div class="row mb-2">
                    <div class="col-md-6">
                        Contact Type {{ $loop->iteration }}: <strong>{{ str_title($contact->type) }}</strong>
                    </div>
                    <div class="col-md-6">
                        Contact Detail {{ $loop->iteration }}: <strong>{{ $contact->detail }}</strong>
                    </div>
                </div>
            @empty
                <strong class="text-center">There are no contacts</strong>
            @endforelse
            <div class="hr-text my-1">Related Links</div>
            @forelse ($responder->relatedLinks as $link)
                <a href="{{ $link->link }}">{{ $link->link }}</a>
            @empty
            <strong class="text-center">There are no link</strong>
            @endforelse
        </div>
    </div>
@endsection
