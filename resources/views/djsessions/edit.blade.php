@extends('layouts/app')

@section('content')
<div class="p-6">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <x-validation-errors/>

        <form action="{{ route('djsessions.update', $djsession) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Header Section with Improved Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <!-- Left Side: Venue and Session Info -->
                <div class="md:col-span-2 flex items-center space-x-6">
                    <!-- Venue Image with Enhanced Styling -->
                    <div class="flex-shrink-0 relative">
                        <img 
                            id="preview-image"
                            src="{{ $djsession->image_url }}" 
                            alt="Imagen Djsession" 
                            class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-xl shadow-lg ring-4 ring-purple-100"
                        >
                        <label for="image" class="absolute -bottom-2 -right-2 bg-purple-600 text-white p-2 rounded-full cursor-pointer hover:bg-purple-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>

                    <!-- Session Details -->
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 mb-2">
                            Sesión <br>
                            <input 
                                type="text" 
                                name="name" 
                                placeholder="Nombre de la sesión" 
                                class="text-purple-600 bg-transparent border-b-2 border-purple-300 focus:border-purple-600 focus:outline-none"
                                value="{{ $djsession->name }}"
                                required
                            >
                        </h1>
                        
                        <!-- Location with Icon -->
                        <p class="mt-2 text-purple-600 font-semibold text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243m0 0L5.636 18.364a9 9 0 1112.728 0l-1.414-1.414z" />
                            </svg>
                            <input 
                                type="text" 
                                name="venue" 
                                placeholder="Local" 
                                class="bg-transparent border-b border-purple-300 focus:border-purple-600 focus:outline-none"
                                value="{{ $djsession->venue }}"
                            >
                            <input 
                                type="text" 
                                name="address" 
                                placeholder="Dirección" 
                                class="bg-transparent border-b border-purple-300 focus:border-purple-600 focus:outline-none"
                                value="{{ $djsession->address }}"
                            >
                            <input 
                                type="text" 
                                name="city" 
                                placeholder="Ciudad" 
                                class="bg-transparent border-b border-purple-300 focus:border-purple-600 focus:outline-none"
                                value="{{ $djsession->city }}"
                            >
                        </p>
                    </div>
                </div>

                <!-- Right Side: Session Metadata -->
                <div class="flex flex-col items-end space-y-3">
                    <!-- Session Code with Auto-generate Option -->
                    <div class="bg-gray-100 rounded-lg px-4 py-2 text-gray-600 flex items-center">
                        <span class="font-bold text-2xl mr-2">#</span>
                        <div class="flex flex-col w-full">
                            <div class="flex items-center">
                                <input 
                                    type="text" 
                                    id="code"
                                    name="code" 
                                    placeholder="Código"
                                    class="font-bold text-2xl bg-transparent border-b border-gray-300 focus:border-gray-600 focus:outline-none w-40 text-center"
                                    value="{{ $djsession->code }}"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="generateRandomCode()"
                                    class="ml-2 p-1 text-purple-600 hover:text-purple-800 transition-colors"
                                    title="Generar código aleatorio"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                            <span class="text-xs text-gray-500 mt-1">Haz clic en el icono para generar un código aleatorio</span>
                        </div>
                    </div>

                    <x-request-timeout timeout="{{ $djsession->song_request_timeout}}" />

                    <!-- Active Status Toggle -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-700">Estado:</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="active" value="1" class="sr-only peer" {{ $djsession->active ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-2 text-sm font-medium text-gray-700 peer-checked:text-purple-600">Activa</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="mt-8 border-b border-gray-200" x-data="{ activeTab: 'summary', loaded:{summary: true, songs: false, tips: false, raffle: false} }">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <x-tab-button tab="summary" color="indigo" x-on:click="activeTab = 'summary'; loaded.summary = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M20.2 7.8l-7.7 7.7-4-4-5.7 5.7"/><path d="M15 7h6v6"/>
                        </svg>
                        Resumen
                    </x-tab-button>
                    <x-tab-button tab="songs" color="purple" x-on:click="activeTab = 'songs'; loaded.songs = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        Canciones
                    </x-tab-button>
                    <x-tab-button tab="tips" color="yellow" x-on:click="activeTab = 'tips'; loaded.tips = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Propinas
                    </x-tab-button>
                    <x-tab-button tab="raffle" color="pink" x-on:click="activeTab = 'raffle'; loaded.raffle = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        Sorteos
                    </x-tab-button>
                </nav>

                <!-- Content Area (Summary - Song Requests - Tips - Raffles) -->
                <div x-show="activeTab === 'summary'" x-cloak>
                    <template x-if="loaded.summary">
                        <livewire:djsession-summary :djsession="$djsession" />
                    </template>
                </div>

                <div x-show="activeTab === 'songs'" x-cloak>
                    <template x-if="loaded.songs">
                        <livewire:song-requests :djsessionId="$djsession->id" />
                    </template>
                </div>

                <div x-show="activeTab === 'tips'" x-cloak>
                    <template x-if="loaded.tips">
                        <div>
                            <livewire:top-donors :djsessionId="$djsession->id" :viewType="'management'" :key="'management'" />
                            <livewire:tips-list :djsessionId="$djsession->id"/>
                        </div>
                    </template>
                </div>

                <div x-show="activeTab === 'raffle'" x-cloak>
                    <template x-if="loaded.raffle">
                        <livewire:djsession-summary :djsession="$djsession" />
                    </template>
                </div>
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-between mt-8">
                <a 
                    href="{{ route('djsessions.show', $djsession) }}" 
                    class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-300 transition-colors flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancelar
                </a>
                
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-purple-600 text-white font-bold rounded-lg shadow-md hover:bg-purple-700 transition-colors flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function generateRandomCode() {
        // Create a random alphanumeric code
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        const length = 6; // Length of code
        
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        
        // Set the generated code to the input field
        document.getElementById('code').value = result;
    }
</script>
@endsection