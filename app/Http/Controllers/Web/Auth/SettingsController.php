<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\SettingsUpdateRequest;
use App\Models\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('auth.settings');
    }

    public function update(SettingsUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->update($request->validated());

        \toastr()->success('Profile updated successfully');

        return redirect(redirectRole($user));
    }

    public function editPassword(): View
    {
        return view('auth.update-password');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->forceFill([
            'password' => bcrypt($request->input('password'))
        ])->save();

        \toastr()->success('Password updated succesfully');

        return redirect(redirectRole($user));
    }
}
