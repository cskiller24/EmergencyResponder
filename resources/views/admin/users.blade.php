@extends('layouts.admin-home')

@section('title')
    Admin Users | Emergency Responser
@endsection

@section('page-title')
    Users
@endsection

@section('content')
    <div class="d-flex justify-content-between my-3">
        <p class="h4">List of users</p>
        <p>Number of users: <strong>{{ $usersCount }}</strong></p>
    </div>
    <form
        action="{{ route('users.index') }}"
        method="get"
    >
        <div class="my-2 row">
            <div class="col-md-10   ">
                <input
                    class="form-control border border-primary"
                    name="s"
                    type="text"
                    value="{{ request('s') }}"
                    placeholder="Search user "
                >
            </div>
            <div class="col-md-2">
                <input
                class="btn btn-info w-100"
                    type="submit"
                    value="Search"
                >
            </div>
        </div>
    </form>
    <div class="table-responsive">
        @if ($users->isNotEmpty())
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @unless($user->email === auth()->user()->email)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <div class="badge bg-primary d-inline-flex align-items-center me-2">
                                            <div class="me-2">
                                                <svg
                                                    class="icon icon-tabler icon-tabler-user-check"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        stroke="none"
                                                        d="M0 0h24v24H0z"
                                                        fill="none"
                                                    ></path>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                                    <path d="M15 19l2 2l4 -4"></path>
                                                </svg>
                                            </div>
                                            <span>{{ str_title($role->name) }}</span>
                                        </div>
                                    @empty
                                        <div class="badge bg-warning d-inline-flex align-items-center me-2">
                                            <div class="me-2">
                                                <svg
                                                    class="icon icon-tabler icon-tabler-user-exclamation"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        stroke="none"
                                                        d="M0 0h24v24H0z"
                                                        fill="none"
                                                    ></path>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.008 .128"></path>
                                                    <path d="M19 16v3"></path>
                                                    <path d="M19 22v.01"></path>
                                                </svg>
                                            </div>
                                            <span>No role</span>
                                        </div>
                                    @endforelse
                                </td>
                            </tr>
                        @endunless
                    @endforeach
                </tbody>
            </table>
        @else
            <h2 class="text-center mt-3">No users</h1>
        @endif

    </div>
@endsection
