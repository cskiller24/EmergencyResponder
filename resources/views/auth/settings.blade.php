@extends(hasRole('admin') ? 'layouts.admin-home' : (hasRole('moderator') ? 'layouts.moderator-home' : 'layouts.user-home'))

@section('title')
    Settings | Emergency Responder
@endsection

@section('page-title')
    Settings
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="row" method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="col-12 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" @class(['form-control', 'is-invalid' => $errors->has('name')]) value="{{ auth()->user()->name }}">
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <label for="name" class="form-label">Email</label>
                    <input type="email" name="name" id="name" @class(['form-control', 'is-invalid' => $errors->has('email')]) value="{{ auth()->user()->email }}">
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <a href="{{ route('settings.password.edit') }}" class="btn w-100 btn-primary">Change my password</a>
                </div>
                <div class="col-12 mb-3">
                    <input type="submit" value="Update Account" class="btn btn-success w-100">
                </div>
            </div>
        </div>
    </div>
@endsection

