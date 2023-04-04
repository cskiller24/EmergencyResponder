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
            @if ($submission->isAuthMaintainer() && $submission->status === \App\Enums\SubmissionStatusEnum::SUBMITTED)
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#submissionUpdate">
                  Update status
                </button>
            @endif

            @if ($submission->hasNoMaintainer())
                <form action="{{ route('moderator.submissions.moderate', $submission->id) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <input class="btn btn-success w-100 btn-block" type="submit" value="Moderate this submission">
                </form>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="submissionUpdate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Update the submission status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <form action="{{ route('moderator.submissions.approve', $submission->id) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <input type="submit" value="Approve" class="btn btn-success w-100 btn-block">
                        </form>
                    </div>

                    <div class="col-12">
                        <form action="{{ route('moderator.submissions.deny', $submission->id) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <input type="submit" value="Deny" class="btn btn-warning w-100 btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
