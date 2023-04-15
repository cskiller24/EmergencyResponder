@extends(hasRole('admin') ? 'layouts.admin-home' : (hasRole('moderator') ? 'layouts.moderator-home' : 'layouts.user-home'))

@section('title')
    Update password | Emergency Responder
@endsection

@section('page-title')
    Update password
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="row" method="POST" action="{{ route('settings.password.update') }}">
                @csrf
                @method('PUT')

                <div class="col-12 mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" id="curent_password" @class(['form-control', 'is-invalid' => $errors->has('current_password')])>
                    <div class="invalid-feedback">{{ $errors->first('current_password') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" @class(['form-control', 'is-invalid' => $errors->has('password')])>
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <input type="submit" value="Change password" class="btn btn-success w-100">
                </div>
            </div>
        </div>
    </div>
@endsection

