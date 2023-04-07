<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->search(request('s'))->get();
        $users = $users->filter(fn (User $user) => $user->email !== auth()->user()->email);
        $usersCount = User::all()->count();

        return view('admin.users', compact('users', 'usersCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $this->authorize('view', User::class);

        $roles = $user->getRoleNames();

        return view('admin.users-show', compact('user', 'roles'));
    }
}
