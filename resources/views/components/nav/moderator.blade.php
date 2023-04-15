<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('moderator.index') }}" class="h2">
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
                <li @class(['nav-item', 'active' => route_named('moderator.submissions.index')])>
                    <a class="nav-link" href="{{ route('moderator.submissions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-browser-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z"></path>
                                <path d="M4 8h16"></path>
                                <path d="M8 4v4"></path>
                                <path d="M9.5 14.5l1.5 1.5l3 -3"></path>
                             </svg>
                        </span>
                        <span class="nav-link-title">
                            Submissions
                        </span>
                    </a>
                </li>

                <li @class(['nav-item', 'active' => route_named('moderator.responders.index')])>
                    <a class="nav-link" href="{{ route('moderator.responders.index') }}">
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

                <li @class(['nav-item', 'active' => route_named('moderator.submissions.index.auth')])>
                    <a class="nav-link" href="{{ route('moderator.submissions.index.auth') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-browser-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z"></path>
                                <path d="M4 8h16"></path>
                                <path d="M8 4v4"></path>
                                <path d="M10 14h4"></path>
                                <path d="M12 12v4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            My submissions
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
