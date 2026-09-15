<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Slip Gaji Karyawan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="page">
        @auth
            <header class="topbar no-print">
                <div class="topbar__inner">
                    <a href="{{ route('dashboard') }}" class="brand">
                        <span class="brand__mark">SG</span>
                        Slip Gaji Karyawan
                    </a>
                    <div class="topbar__user">
                        <span>{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>
        @endauth

        <main class="content">
            @if (request()->routeIs('login'))
                @yield('content')
            @else
                <div class="container">
                    @yield('content')
                </div>
            @endif
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
