<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="{{ asset('dist/tabler/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/tabler/css/tabler-flags.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/tabler/css/tabler-payments.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/tabler/css/tabler-vendors.min.css') }}">

    <title>
        @yield('title', 'EResponder')
    </title>
</head>
<body class="h-100">
    <div class="d-flex justify-content-center align-items-center">
        @yield('content')
    </div>
    <script src="{{ asset('dist/tabler/js/tabler.min.js') }}"></script>
</body>
</html>
