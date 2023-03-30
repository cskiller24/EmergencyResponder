@extends('layouts.moderator-home')

@section('title')
    Submissions | Emergency Responder
@endsection

@section('page-title')
Submission
@endsection

@section('content')
<div class="d-flex justify-content-between">
    <h4>List of submissions</h4>
    <p>
        Number of submissions: {{ $submissionsCount }}
    </p>
</div>
<form action="{{ route('moderator.submissions.index') }}" method="GET">
    <div class="my-2 row">
        <div class="col-md-6 mb-2">
            <input type="text" name="s" id="search" class="form-control" placeholder="Search..." value="{{ request('s') }}">
        </div>
        <div class="col-md-2 mb-2">
            <select name="f" class="form-select">
                <option selected disabled>Select Filters</option>
                @forelse ($statuses as $status)
                <option value="{{ $status->value }}" class="dropdown-item" >{{ $status->titleCase() }}</option>
                @empty
                <option disabled>There are no status</option>
                @endforelse
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <select name="p" class="form-select">
                <option selected disabled>Select Page</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <input type="submit" value="Search" class="btn btn-primary w-100">
        </div>
    </div>
</form>
<div class="table-responsive">
    @if ($submissions->isNotEmpty())
    <table class="table table-vcenter">
        <thead class="sticky-top">
            <tr>
                <td>Name</td>
                <td>Contact Detail</td>
                <td>City</td>
                <td>Emergency Type</td>
                <td>Status</td>
                <td>Submitter</td>
                <td>Monitored by</td>
                <td>Updated At</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($submissions as $submission)
                <tr>
                    <td>
                        <a href="{{ route('moderator.submissions.show', $submission->id) }}" class="link-primary">{{ $submission->name }}</a>
                    </td>
                    <td>{{ $submission->contacts->first()->detail }}</td>
                    <td>{{ $submission->location->city }}</td>
                    <td>{{ $submission->emergencyType->name }}</td>
                    <td>{{ $submission->status->titleCase() }}</td>
                    <td>{{ $submission->submittedBy->email }}</td>
                    <td>{{ $submission->monitoredBy->email }}</td>
                    <td>{{ $submission->updated_at->diffForHumans() }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {!! $submissions->withQueryString()->render() !!}
    </div>
    @else
    <h2>No submissions, check your search and filter.</h2>
    @endif
</div>
@endsection
