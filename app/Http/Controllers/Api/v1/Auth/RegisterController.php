<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class RegisterController extends Controller
{
    public function __invoke(Request $request, CreateNewUser $creator)
    {
        event(new Registered($user = $creator->create($request->all())));

        $user->assignRole('user');

        return response()->json(['message' => 'Created user successfully'], ResponseCodes::HTTP_CREATED);
    }
}
