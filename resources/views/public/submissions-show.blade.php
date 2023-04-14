@extends('layouts.user-home')

@section('title')
    {{ $submission->name }} Submission | Emergency Responder
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-2">
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
                <p class="card-text">{{ $submission->emergencyType->name }}</p>

                <div class="my-2 hr-text"></div>
                <p class="h4">Handlers</p>
                <div class="d-flex justify-content-between">
                    <div>Submitted by: <b>{{ $submission->submittedBy->name }}</b></div>
                    <div>Monitored by: <b>{{ $submission->monitoredBy->name ?? 'No monitor yet' }}</b></div>
                </div>

                <div class="my-2 hr-text"></div>
                <p class="h4">Location</p>
                <p>{{ $submission->location->fullAddress }}</p>

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
    @if ($submission->canEdit())
        <a class="btn btn-success" href="{{ route('public.submission.edit', $submission->id) }}">
          Update my submission
        </a>
    @endif
</div>
</div>

@endsection

