<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'Admin | EResponser')</title>
    <!-- CSS files -->
    @include('partials.tabler-styles')
</head>

<body>
    @if ($withToast ?? true)
        @include('components.toastr')
    @endif
    <div class="page">
    <!-- Sidebar -->
    @include('components.nav.admin')
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col ms-1">
                            <!-- Page pre-title -->
                            <div class="page-pretitle">
                                @yield('pre-title', 'ADMIN')
                            </div>
                            <h2 class="page-title">
                                @yield('page-title', 'Dashboard')
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <a href="#" class="nav-link mx-3">Notifications</a>
                        </div>
                        <div class="col-auto ms-auto d-print-none d-none d-lg-flex">
                            <div class="btn-list">
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
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    @include('partials.tabler-scripts')
</body>

</html>
