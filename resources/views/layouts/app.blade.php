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
    <body x-data="{ showLoader: false }"
        x-on:show-global-loader.window="showLoader = true"
        class="font-sans antialiased relative">
        <x-banner />

        {{-- Loader pantalla completa --}}
        <div x-show="showLoader"
            x-transition.opacity
            class="fixed inset-0 z-50 pointer-events-auto overflow-hidden"
            style="display: none;">
            <x-loader />
        </div>

        {{-- Contenido principal --}}
        <div class="flex flex-col min-h-screen">
            @include('layouts.partials_.header')
            <div class="flex flex-1">
                @include('layouts.partials_.sidebar')
                <div class="flex-1 p-6 bg-gray-100">
                    @yield('content')
                </div>
            </div>
        </div>

        @livewireScripts
    </body>

</html>
