@extends('layouts.guest')

@section('title')
    Reset Password | Emergency Responder
@endsection

@section('content')
    <div class="page page-center w-50">
        <div class="container container-tight py-4">

            <form class="card card-md mt-4" action="{{ route('password.update') }}" method="POST" autocomplete="off"
                novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Reset your password</h2>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <div class="text-muted">{{ session('status') }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) placeholder="Enter email" name="email"
                            value="{{ $request->email }}">
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) placeholder="Enter password" name="password"
                            >
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Enter password confirmation" name="password_confirmation"
                            >
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            Reset my password
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center text-muted mt-3">
                Forget it, <a href="{{ route('login') }}">send me back</a> to the log in screen.
            </div>
        </div>
    </div>
@endsection
