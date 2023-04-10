@extends('layouts.moderator-home')

@section('title')
    Responders | Emergency Responder
@endsection

@section('page-title')
Responders
@endsection

@section('content')
<div class="d-flex justify-content-between">
    <h4>List of submissions</h4>
    <p>
        Number of responders: {{ $respondersCount }}
    </p>
</div>
<form action="{{ route('moderator.responders.index') }}" id="filter-form" method="GET" >
    <div class="my-2 row">
        <div class="col-md-6 mb-2">
            <input type="text" name="s" id="search" class="form-control" placeholder="Search..." value="{{ request('s') }}">
        </div>
        <div class="col-md-2 mb-2">
            <select name="f" class="form-select" id="filter">
                <option selected disabled>Select filter</option>
                <option value="nearest">Nearest</option>
                <option value="farthest">Farthest</option>
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
    @if ($responders->isNotEmpty())
    <table class="table table-vcenter">
        <thead class="sticky-top">
            <tr>
                <td>Name</td>
                <td>Contact Detail</td>
                <td>City</td>
                <td>Emergency Type</td>
                <td>Updated At</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($responders as $responder)
                <tr>
                    <td>
                        <a href="{{ route('moderator.responders.show', $responder->id) }}" class="link-primary">{{ $responder->name }}</a>
                    </td>
                    <td>{{ $responder->contacts->first()->detail }}</td>
                    <td>{{ $responder->location->city }}</td>
                    <td>{{ $responder->emergencyType->name }}</td>
                    <td>{{ $responder->updated_at->diffForHumans() }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {!! $responders->withQueryString()->render() !!}
    </div>
    @else
    <h2>No submissions, check your search and filter.</h2>
    @endif
</div>
@endsection

@push('geolocation-script')
    <script>
        const form = document.getElementById('filter-form');

        // Add an event listener for form submission
        form.addEventListener('submit', function() {
        // Check if the filter value is 'nearest' or 'farthest'
        const filter = document.querySelector('#filter').value;
        if (filter === 'nearest' || filter === 'farthest') {
            // Get the user's location
            navigator.geolocation.getCurrentPosition(function(position) {
            // Add the latitude and longitude to the form as hidden inputs
            const latInput = document.createElement('input');
            latInput.type = 'hidden';
            latInput.name = '_latitude';
            latInput.value = position.coords.latitude;
            form.appendChild(latInput);

            const longInput = document.createElement('input');
            longInput.type = 'hidden';
            longInput.name = '_longitude';
            longInput.value = position.coords.longitude;
            form.appendChild(longInput);

            // Submit the form
            form.submit();
            });
        }
        });
    </script>
@endpush
