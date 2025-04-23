<div 
    class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md w-full max-w-5xl mx-auto hover:bg-gray-50 transition-colors duration-200 {{ $role === 'dj' ? 'hover:bg-gray-50 cursor-pointer' : '' }}"
    @if($role === 'dj') 
        onclick="window.location='{{ route('djsessions.show', $djsession) }}'" 
        style="cursor: pointer;" 
    @endif
>
    <!-- Imagen del local -->
    <div class="w-28 h-28 rounded overflow-hidden mr-4">
        <img src="{{ $djsession->image_url }}" alt="{{ $djsession->name }}" class="object-cover w-full h-full">
    </div>
    
    <!-- Info principal -->
    <div class="flex-1">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-extrabold">{{ $djsession->name }}</h2>
            <span class="text-gray-400 font-bold text-lg">#{{ $djsession->code }}</span>
        </div>
        <p class="text-purple-600 font-semibold text-sm flex items-center mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243m0 0L5.636 18.364a9 9 0 1112.728 0l-1.414-1.414z" />
            </svg>
            {{ $location }}
        </p>
        
        @if($showUserOptions)
            <div class="flex items-center mt-2">
                <img src="{{ asset($djAvatar) }}" alt="{{ $djName }}" class="w-6 h-6 rounded-full mr-2">
                <span class="text-sm font-semibold">{{ $djName }}</span>
                <button wire:click="followDj" class="ml-2 text-xs border px-2 py-0.5 rounded hover:bg-gray-100">Seguir</button>
            </div>
        
            <div class="flex space-x-2 mt-4">
                <button wire:click="requestSong" class="bg-pink-500 text-white font-bold text-sm px-4 py-2 rounded flex items-center">
                    Cancion
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4a1 1 0 011-1h6a1 1 0 011 1v10a3 3 0 11-2-2.83V7h-4v7a3 3 0 11-2-2.83V4z"/></svg>
                </button>
                <button wire:click="giveTip" class="bg-yellow-400 text-black font-bold text-sm px-4 py-2 rounded flex items-center">
                    Propina
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12H9v2H7v2h2v2H7v2h2v2h2v-2h2v-2h-2v-2h2V8h-2V6z"/></svg>
                </button>
                <button wire:click="enterRaffle" class="bg-purple-600 text-white font-bold text-sm px-4 py-2 rounded flex items-center">
                    Sorteo
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zM3 6a1 1 0 011 1v9a2 2 0 002 2h8a2 2 0 002-2V7a1 1 0 112 0v9a4 4 0 01-4 4H6a4 4 0 01-4-4V7a1 1 0 011-1z"/></svg>
                </button>
            </div>
        @endif
    </div>
    
    <!-- Botón Entrar/Salir y participantes -->
    <div class="flex flex-col items-end justify-between h-full ml-4">
    
        @if($isCurrentDjsession)
            <x-danger-button wire:click="setCurrent">
                {{ $role == 'dj' ? 'Desactivar' : 'Salir' }}   
            </x-danger-button>
        @else
            <x-button wire:click="setCurrent" :disabled="$role == 'user' && !$djsession->active">
                {{ $role == 'dj' ? 'Activar' : 'Unirse' }}
            </x-button>
        @endif
        <div class="text-sm text-black mt-4 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6v4l5-5-5-5v4z"/></svg>
            {{ $djsession->current_users }} Participantes
        </div>
    </div>
</div>
