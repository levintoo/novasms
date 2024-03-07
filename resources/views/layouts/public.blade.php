<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @stack('head')
        <title>Home - {{ config('app.name', 'Novasms') }}</title>
        <link rel="icon" href="{{ asset('icon.png') }}" />
        <!-- Scripts -->
        @livewireStyles
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-gray-100/50">
        @include('shared.header')
        <main class="container mx-auto max-w-7xl">
            @yield('content')
        </main>
        @include('shared.footer')
        @stack('scripts')
    @livewireScripts
    </body>
</html>
