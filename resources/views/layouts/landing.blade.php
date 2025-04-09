{{-- resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DJ-PONTE') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-black flex">
        <!-- Columna izquierda: Imagen de fondo con texto -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-purple-900 to-blue-900">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/general/dj-background.jpg') }}'); opacity: 0.8;"></div>
            <div class="relative z-10 flex flex-col justify-center px-12">
                <h1 class="text-5xl font-bold text-white leading-tight">
                    La mejor<br>
                    página para<br>
                    la gente<br>
                    fiestera
                </h1>
                <p class="mt-6 text-white text-lg">¡DJ ponte la nueva de Beethoven!</p>
            </div>
        </div>

        <!-- Columna derecha: Formulario de autenticación -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-start bg-white">

            <!-- Logo arriba en barra negra -->
            <div class="w-full bg-black py-6 flex justify-center">
                <img src="{{ asset('storage/icons/djponte-logo.svg') }}" alt="DJ-PONTE" class="h-12 lg:h-14">
            </div>

            @yield('content')
        </div>
    </div>

    @livewireScripts
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>