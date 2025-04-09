<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DJ-PONTE') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="flex flex-col min-h-screen">

            <!-- Page Heading -->
            @include('layouts.partials_.header')
            <!-- Contenedor de contenido (sidebar + contenido principal) -->
            <div class="flex flex-1">
                @include('layouts.partials_.sidebar')
                <!-- Contenido principal -->
                <div class="flex-1 p-6 bg-gray-100">
                    <!-- Aquí iría el contenido principal de tu aplicación -->
                    @yield('content')
                </div>
            </div>
            <!-- Page Content -->
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
