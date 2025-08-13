<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'DJ-PONTE') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased relative bg-gray-100 dark:bg-gray-900 overflow-x-hidden">
        <x-banner />

        <div x-data="{ isSidebarOpen: window.innerWidth >= 1024 }">

            @include('layouts.partials_.sidebar')
            
            <div class="relative flex flex-col min-h-screen transition-all duration-300" :class="{ 'lg:ml-72': isSidebarOpen }">

                @include('layouts.partials_.header')
                
                <main class="flex-grow">
                    @yield('content')
                </main>

                @include('layouts.partials_.footer')
            </div>

            <div x-show="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/60 z-30 lg:hidden" x-cloak></div>

        </div>

        @livewireScripts
    </body>
</html>
