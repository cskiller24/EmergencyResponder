@extends('layouts.user-home')

@section('title')
    Submissions | Emergency Responders
@endsection

@section('page-title')
    List of Submissions
@endsection


@section('content')
    <form action="{{ route('public.submissions.index') }}" id="filter-form" method="GET" >
        <div class="my-2 row">
            <div class="col-md-6 mb-2">
                <input type="text" name="s" id="search" class="form-control" placeholder="Search..." value="{{ request('s') }}">
            </div>
            <div class="col-md-2 mb-2">
                <select name="f" class="form-select" id="filter">
                    <option disabled selected>Select Filter</option>
                    <option value="nearest" >Nearest</option>
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
    @forelse ($submissions as $submission)
        <a @class(['card', 'mt-2']) href="{{ route('public.submissions.show', $submission->id) }}">
            <div class="card-body">
                <div class="card-title">{{ $submission->name }}</div>
                <p>{{ str($submission->description)->limit(70) }}</p>
                <p>Location: <strong>{{ $submission->location->fullAddress }}</strong></p>
            </div>
        </a>
    @empty
        <h1 class="text-center">Empty Submissions</h1>
    @endforelse
    <div class="my-2">
        {{ $submissions->withQueryString()->render() }}
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
