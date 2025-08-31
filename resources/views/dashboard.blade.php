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
                @if (Auth::user()->role === 'dj')
                    {{-- Componente personalizado para el dashboard del DJ --}}
                    <x-dj-dashboard></x-dj-dashboard>
                @else
                    {{-- Contenido para usuarios estándar --}}
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