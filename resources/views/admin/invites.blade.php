@php
    $modalData = [
        'modalId' => 'createInviteModal',
        'modalTitle' => 'Create Invite',
        'modalSaveButton' => 'Invite',
    ];
@endphp

@extends('layouts.admin-home')

@section('title')
    Admin Invitations | Emergency Responser
@endsection

@section('page-title')
    Invitations
@endsection

@section('content')
    <div class="d-flex justify-content-between my-3">
        <p class="h4">List of invites</p>
        <p>Number of invites: <strong>{{ $invites->count() }}</strong></p>
    </div>
    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createInviteModal">
        Invite a user</button>
    <div>
        @if ($invites)
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invites as $invite)
                            <tr>
                                <td>{{ $invite->email }}</td>
                                <td>{{ $invite->code }}</td>
                                <td>{{ str_title($invite->role) }}</td>
                                <td class="justify-content-between">
                                    <form action="{{ route('admin.invites.resend', $invite->code) }}" method="post">
                                        @csrf
                                        <button class="btn btn-primary" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-send" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M10 14l11 -11"></path>
                                                <path
                                                    d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5">
                                                </path>
                                            </svg>
                                            Resend
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h2 class="text-center">No invitations.</h2>
        @endif
    </div>

    @include('components.modals.invites', $modalData)
@endsection
