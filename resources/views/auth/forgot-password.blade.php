@extends('layouts.guest')

@section('title')
    Forgot Password | Emergency Responder
@endsection

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">

            <form class="card card-md mt-4" action="{{ route('password.email') }}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Forgot password</h2>
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <div class="text-muted">{{ session('status') }}</div>
                    </div>
                    @endif
                    <p class="text-muted mb-4">Enter your email address and your password will be reset and emailed to you.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) placeholder="Enter email" name="email"
                            value="{{ old('email') }}">
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                <path d="M3 7l9 6l9 -6" />
                            </svg>
                            Send me new password
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
