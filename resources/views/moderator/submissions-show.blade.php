@extends('layouts.moderator-home')

@section('title')
{{ $submission->name }} | Emergency Responder
@endsection

@section('page-title')
Submission
@endsection

@section('content')
<div class="card border-0 rounded-0">
    <div class="card-body">
        <div class="card-title">
            <h2>Submission</h2>
        </div>
        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-title">{{ $submission->name }}</div>
                            <div @class(['badge', 'bg-red'=> $submission->status->isDeclined(), 'bg-green' =>
                                $submission->status->isApproved(), 'bg-yellow' => $submission->status->isSubmitted(),
                                'bg-teal' => $submission->status->isDraft()])>{{ $submission->status->getMessage() }}
                            </div>
                        </div>
                        <p class="card-text">{{ $submission->description }}</p>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Handlers</p>
                        <div class="d-flex justify-content-between">
                            <div>Submitted by: <b>{{ $submission->submittedBy->name }}</b></div>
                            <div>Monitored by: <b>{{ $submission->monitoredBy->name ?? 'No monitor yet' }}</b></div>
                        </div>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Location</p>
                        <p>{{ $submission->location->fullAddress() }}</p>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Links</p>
                        <ul class="list-group list-group-numbered">
                            @forelse ($submission->relatedLinks as $relatedLink)
                            <li class="list-group-item">{{ $relatedLink->link }}</li>
                            @empty
                            <p class="h5">Empty Links</p>
                            @endforelse
                        </ul>

                        <div class="my-2 hr-text"></div>
                        <p class="h4">Contacts</p>
                        <ul class="list-group list-group-numbered">
                            @forelse ($submission->contacts as $contact)
                            <li class="list-group-item">{{ str_title($contact->type) }} - <b>{{ $contact->detail }}</b></li>
                            @empty
                            <p class="h5">Empty Contacts</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            @if ($submission->isAuthMaintainer())

            @endif
        </div>
    </div>
</div>
@endsection
