<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('admin.index') }}" class="h2">
                EResponder
            </a>
        </h1>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item">
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Hello
                        {{ auth()->user()->name }}!</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('settings.edit') }}">Settings</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li @class(['nav-item', 'active' => route_named('admin.index')])>
                    <a class="nav-link" href="{{ route('admin.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Dashboard
                        </span>
                    </a>
                </li>
                <div class="hr-text text-light mt-3 mb-1">Users</div>
                <li @class(['nav-item', 'active' => route_named('admin.users.index')]) >
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Users
                        </span>
                    </a>
                </li>
                <li @class(['nav-item', 'active' => route_named('admin.invites.index')])>
                    <a class="nav-link" href="{{ route('admin.invites.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail-forward"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5"></path>
                                <path d="M3 6l9 6l9 -6"></path>
                                <path d="M15 18h6"></path>
                                <path d="M18 15l3 3l-3 3"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Invitations
                        </span>
                    </a>
                </li>
                <div class="hr-text text-light mt-3 mb-1">Core</div>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-location"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5">
                                </path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Responders
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-browser-plus"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z">
                                </path>
                                <path d="M4 8h16"></path>
                                <path d="M8 4v4"></path>
                                <path d="M10 14h4"></path>
                                <path d="M12 12v4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Submissions
                        </span>
                    </a>
                </li>
                <li @class(['nav-item', 'active' => route_named('admin.emergency-types.index')])>
                    <a class="nav-link" href="{{ route('admin.emergency-types.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                <path d="M12 8v4"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Emergency Types
                        </span>
                    </a>
                </li>
                <div class="hr-text text-light mt-3 mb-1">Authorizations</div>
                <li @class(['nav-item', 'active' => route_named('admin.roles.index')])>
                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                <path d="M15 19l2 2l4 -4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Roles
                        </span>
                    </a>
                </li>
                <li @class(['nav-item', 'active' => route_named('admin.permissions.index')])>
                    <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-check"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v.5"></path>
                                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                                <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                                <path d="M15 19l2 2l4 -4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Permissions
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
