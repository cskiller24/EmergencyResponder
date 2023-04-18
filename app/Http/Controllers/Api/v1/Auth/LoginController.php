<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        if (! auth()->attempt($request->validated())) {
            return response()->json(['message' => 'Invalid provide credentials']);
        }

        $user = User::query()->where('email', $request->email)->first();

        $user->token = $user->createToken($user->name)->plainTextToken;

        return LoginResource::make($user);
    }
}
