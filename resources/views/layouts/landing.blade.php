{{-- /resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DJPonte') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-black">

    <div x-data="{ showLoader: false }" 
         x-on:show-global-loader.window="showLoader = true" 
         x-show="showLoader"
         x-transition.opacity
         class="fixed inset-0 z-50 pointer-events-auto overflow-hidden"
         style="display: none;">
        <x-loader />
    </div>

    <main>
        @yield('content')
    </main>
    @include('layouts.partials_.footer')

    @livewireScripts
</body>
</html>