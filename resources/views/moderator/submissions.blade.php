@extends('layouts.moderator-home')

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
        <div class="col-md-8 mb-2">
            <input type="text" name="s" id="search" class="form-control" placeholder="Search..." value="{{ request('s') }}">
        </div>
        <div class="col-md-2 mb-2">
            <select name="filter" class="form-select">
                <option selected disabled>Select Filters</option>
                @forelse ($statuses as $status)
                <option value="{{ $status->value }}" class="dropdown-item" @selected(request('filter') == $status->value)>{{ $status->titleCase() }}</option>
                @empty
                <option disabled>There are no status</option>
                @endforelse
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
        <thead>
            <tr>
                <td>Name</td>
                <td>Contact Detail</td>
                <td>City</td>
                <td>Emergency Type</td>
                <td>Status</td>
                <td>Submitter</td>
                <td>Monitored by</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($submissions as $submission)
                <tr>
                    <td>{{ $submission->name }}</td>
                    <td>{{ $submission->contacts->first()->detail }}</td>
                    <td>{{ $submission->location->city }}</td>
                    <td>{{ $submission->emergencyType->name }}</td>
                    <td>{{ parse_status($submission->status) }}</td>
                    <td>{{ $submission->submittedBy->email }}</td>
                    <td>{{ $submission->monitoredBy->email }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <h2>No submissions, check your search and filter.</h2>
    @endif
</div>
@endsection
