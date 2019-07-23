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
    <!-- Body -->
    @yield('body')

    <!-- Scripts -->
    @stack('js')
</body>
</html>
