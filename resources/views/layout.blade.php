<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- TODO: Favicons --}}

    <!-- Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Styles -->
    @stack('css')
</head>
<body>
    <header>
        <p>Is logged in: {{ auth('web')->check() ? 'Yes' : 'No' }}</p>
        @if (auth('web')->check())
            <form action="{{ route('auth.admin.logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>

            @if (session('resent'))
                <p>Email verification has been sent.</p>
            @elseif (!auth('web')->user()->hasVerifiedEmail())
                <p>Email not verified <a href="{{ route('auth.end-user.verification.resend') }}">click here to resend</a>.</p>
            @endif
        @endif
    </header>

    <!-- Body -->
    @yield('body')

    <!-- Scripts -->
    @stack('js')
</body>
</html>
