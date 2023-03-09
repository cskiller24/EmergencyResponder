@extends('layouts.guest')

@section('title')
Login | Emergency Responder
@endsection

@section('content')
<div class="page page-center w-50">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" @class(['form-control', 'is-invalid'=> $errors->has('email')])
                        placeholder="your@email.com" required autofocus value="{{ old('email') }}">
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="./forgot-password.html">I forgot password</a>
                            </span>
                        </label>
                            <input type="password" name="password" @class(['form-control', 'is-invalid'=> $errors->has('password')])
                            placeholder="Your password" autocomplete="password" required autofocus>
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Log in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Register</a>
        </div>
    </div>
</div>
@endsection
