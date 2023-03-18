<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\RegisterResponse;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, CreateNewUser $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        $user->assignRole('user');

        return app(RegisterResponse::class);
    }
}
