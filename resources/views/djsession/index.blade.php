@extends('layouts/app')

@section('content')
    @if($role == 'dj')
        @if($djsessionActive)
            @livewire('djsession-card', [
                'djsession' => $djsessionActive,
                'location' => $djsessionActive->fullLocation(),
                'djName' => $djName,
                'djAvatar' => $djAvatar,
                'role' => $role
            ]) //
        @endif
        @foreach($djsessions as $djsession)
            @include('components.djsession-card', [
                'djsession' => $djsession,
                'location' => $djsession->fullLocation(),
                'djName' => $djName,
                'djAvatar' => $djAvatar,
            ])

        @endforeach
        <a href="{{ route('djsessions.create') }}" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-pink-600 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Crear Nueva Sesión
        </a>
    @elseif($role == 'user')
        @if($djsessionActive)
            @livewire('djsession-card', [
                'djsession' => $djsessionActive,
                'location' => $djsessionActive->fullLocation(),
                'djName' => $djName,
                'djAvatar' => $djAvatar,
                'role' => $role
            ]) //
        @else
            <div class="flex justify-center">
                <h1 class="text-2xl font-bold">No hay sesiones activas</h1>
            </div>
        @endif
        <form action="{{ route('djsession.search') }}" method="GET" class="relative flex items-center w-full max-w-md mx-auto">
            <input 
                type="text" 
                name="query" 
                placeholder="Buscar" 
                class="w-full px-4 py-2 pr-10 text-sm bg-white border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                value="{{ request('query') }}"
            >
            <button 
                type="submit" 
                class="absolute right-2 p-1 text-gray-400 transition-colors duration-200 rounded-full hover:text-pink-500 focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
        
    @endif

    
@endsection