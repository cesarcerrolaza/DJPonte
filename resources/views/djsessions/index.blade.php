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
            ])
            <br>
        @endif
        @foreach($djsessions as $djsession)
            @include('components.djsession-card', [
                'djsession' => $djsession,
                'location' => $djsession->fullLocation(),
                'djName' => $djName,
                'djAvatar' => $djAvatar,
            ])
            <br>
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
            ])
            <br>
        @else
            <div class="flex justify-center">
                <h1 class="text-2xl font-bold">No hay sesiones activas</h1>
            </div>
        @endif

        
    @endif

    
@endsection