@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-black">
    {{-- Imagen de fondo y superposición oscura --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('storage/general/dj-background.jpg') }}" alt="DJ Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-75"></div>
    </div>

    {{-- Contenedor del contenido, que se superpone al fondo --}}
    <div class="relative z-10 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @auth
                {{-- ========================================================== --}}
                {{-- |                VISTA PARA EL ROL 'DJ'                  | --}}
                {{-- ========================================================== --}}
                @if (Auth::user()->role === 'dj')
                    
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-white">Bienvenido de nuevo, {{ Auth::user()->name }}</h1>
                        <p class="text-gray-400">Aquí tienes un resumen de tu actividad.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        {{-- Columna Principal (2/3 de ancho) --}}
                        <div class="lg:col-span-2 space-y-8">
                            
                            {{-- Widget de Sesión Activa o Crear Sesión --}}
                            <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 border border-gray-700">
                                @php
                                    $activeSession = null; // Cambia esto por tu lógica real desde el controlador
                                @endphp

                                @if($activeSession)
                                    <h2 class="text-2xl font-bold text-white mb-2">Sesión en Vivo: {{ $activeSession->name }}</h2>
                                    <p class="text-gray-400 mb-4">Tu público está interactuando ahora mismo.</p>
                                    <div class="flex items-center space-x-6 text-white mb-6">
                                         {{-- ... Contadores de estadísticas ... --}}
                                    </div>
                                    <a href="{{-- route('djsessions.show', $activeSession->id) --}}" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">
                                        ▶️ Ir al Panel en Vivo
                                    </a>
                                @else
                                    <h2 class="text-2xl font-bold text-white mb-2">¿Listo para empezar?</h2>
                                    <p class="text-gray-400 mb-4">No tienes ninguna sesión activa en este momento.</p>
                                    <a href="{{ route('djsessions.create') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">
                                        + Crear Nueva Sesión
                                    </a>
                                @endif
                            </div>

                            {{-- Widget de Feed de Actividad Reciente --}}
                            <div>
                                 <h3 class="text-xl font-semibold text-white mb-4">Actividad Reciente</h3>
                                 <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 text-gray-300 border border-gray-700">
                                     <p>Aquí se mostrarían las últimas peticiones, propinas, etc.</p>
                                     <p class="mt-4 text-sm text-gray-500">(Placeholder para el componente Livewire de actividad)</p>
                                 </div>
                            </div>

                        </div>

                        {{-- Columna Lateral (1/3 de ancho) --}}
                        <div class="space-y-8">
                            {{-- Widget de Accesos Rápidos --}}
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-4">Accesos Rápidos</h3>
                                <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 space-y-4 border border-gray-700">
                                    <a href="{{ route('djsessions.index') }}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Ver todas mis sesiones</a>
                                    <a href="{{ route('profile.show') }}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Gestionar mi perfil</a>
                                    <a href="{{-- route('social.management') --}}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Conectar Redes Sociales</a>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- ========================================================== --}}
                {{-- |              VISTA PARA EL ROL 'USER'                  | --}}
                {{-- ========================================================== --}}
                @else
                    <div class="min-h-[80vh] flex flex-col items-center justify-center text-center">
                        <div class="w-full max-w-lg">
                            <h1 class="text-4xl font-extrabold text-white mb-4">Únete a la Fiesta</h1>
                            <p class="text-gray-400 mb-8">Introduce el código del evento o el nombre del DJ para empezar a pedir canciones.</p>
                            <form action="{{ route('djsessions.search') }}" method="GET" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="query" placeholder="Código o nombre del DJ..." class="w-full p-4 bg-gray-700/80 backdrop-blur-sm border-2 border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition-transform transform hover:scale-105">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
            
        </div>
    </div>
</div>
@endsection