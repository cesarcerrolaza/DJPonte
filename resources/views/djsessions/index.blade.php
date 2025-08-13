@extends('layouts/app')

@section('content')
<div class="p-6">
    @if($djsessionActive)
        {{-- TÍTULO PARA LA SESIÓN ACTIVA --}}
        <h2 class="text-2xl font-black text-pink-600 mb-4">Sesión Activa</h2>
        
        <div>
            <livewire:djsession-card
                :djsession="$djsessionActive"
                :location="$djsessionActive->fullLocation()"
                :djName="$djName"
                :djAvatar="$djAvatar"
                :role="$role"
                :key="'djsession-card-' . $djsessionActive->id"
            />
        </div>
    @endif

    @if($role == 'dj')
        <div class="flex justify-center my-8">
            <a href="{{ route('djsessions.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-pink-600 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Nueva Sesión
            </a>
        </div>
        
        {{-- SEPARADOR Y TÍTULO --}}
        @if($djsessions->isNotEmpty())
            <div class="relative py-8">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-lg font-medium text-gray-900">Otras Sesiones</span>
                </div>
            </div>
            
            @foreach($djsessions as $djsession)
                @include('components.djsession-card', [
                    'djsession' => $djsession,
                    'location' => $djsession->fullLocation(),
                    'djName' => $djName,
                    'djAvatar' => $djAvatar,
                ])
                <br>
            @endforeach
        @endif
    
    @elseif($role == 'user' && !$djsessionActive)
        <div class="flex justify-center">
            <h1 class="text-2xl font-bold">No hay sesiones activas</h1>
        </div>
    @endif
</div>

@endsection