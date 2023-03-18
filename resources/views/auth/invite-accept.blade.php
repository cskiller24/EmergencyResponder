@extends('layouts.guest')

@section('title')
Register | Emergency Responder
@endsection

@section('content')
<div class="page page-center w-50">
    <div class="container container-tight py-4">
        <form class="card card-md" action="{{ route('invites.register',) }}" method="POST">
            @csrf
            <div class="card-body">
                <h2 class="card-title text-center mb-4 h2">Create new account</h2>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" @class(['form-control', 'is-invalid'=> $errors->has('name')]) placeholder="Enter name" value="{{ old('name') }}">
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" @class(['form-control', 'is-invalid'=> $errors->has('email')]) placeholder="Enter email" value="{{ $invite->email }}" readonly >
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" @class(['form-control', 'is-invalid'=> $errors->has('password')]) placeholder="Password" autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" @class(['form-control', 'is-invalid'=> $errors->has('password')]) placeholder="Confirm your password" autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Already have account? <a href="{{ route('login') }}" tabindex="-1">Log in</a>
        </div>
    </div>
</div>
@endsection
