<div class="container mx-auto px-4 py-6 max-w-6xl">
    <!-- Header Section with Improved Layout -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <!-- Left Side: Venue and Session Info -->
        <div class="md:col-span-2 flex items-center space-x-6">
            <div class="flex-shrink-0">
                <img 
                    src="{{ $djsession->image_url }}" 
                    alt="Imagen Djsession" 
                    class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-xl shadow-lg ring-4 ring-purple-100"
                >
                <div class="flex justify-center mt-2 space-x-2">
                    <a href="/instagram/connect" class="p-2 rounded-full bg-purple-50 hover:bg-purple-100 transition" title="Conectar Instagram">
                        <img src="{{ asset('storage/icons/instagram.svg') }}" alt="Instagram" class="w-5 h-5">
                    </a>
                    <a href="/tiktok/connect" class="p-2 rounded-full bg-purple-50 hover:bg-purple-100 transition" title="Conectar TikTok">
                        <img src="{{ asset('storage/icons/tiktok.svg') }}" alt="TikTok" class="w-5 h-5">
                    </a>
                </div>
            </div>

            <!-- Session Details -->
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-800 mb-2">
                    Sesión <br>
                    <span class="text-purple-600">{{ $djsession->name }}</span>
                </h1>
                
                <!-- Location with Icon -->
                <p class="mt-2 text-purple-600 font-semibold text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243m0 0L5.636 18.364a9 9 0 1112.728 0l-1.414-1.414z" />
                    </svg>
                    {{ $location }}
                </p>
            </div>
        </div>

        <!-- Right Side: Session Metadata -->
        <div class="flex flex-col items-end space-y-3">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <button class="p-2 rounded-full hover:bg-gray-200 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 10a2 2 0 114.001.001A2 2 0 016 10zm4-4a2 2 0 110-4 2 2 0 010 4zm0 12a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-button wire:click="$set('confirmingSessionDeletion', true)" class="text-red-600 dark:text-red-400">
                        Eliminar sesión
                    </x-dropdown-button>

                    <x-dropdown-link :href="route('djsessions.edit', $djsession)">
                        Editar sesión
                    </x-dropdown-link>

                    <x-dropdown-button wire:click="copyUrl({{ $djsession->id }})">
                        Duplicar sesión
                    </x-dropdown-button>
                </x-slot>
            </x-dropdown>
            <!-- Session Code with Modern Design -->
            <div class="bg-gray-100 rounded-full px-4 py-1 text-gray-600">
                <span class="font-bold text-2xl">#{{ $djsession->code }}</span>
            </div>

            <!-- Participants with Icon -->
            <div class="flex items-center text-gray-700 space-x-2">
                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.5 17.5a5.5 5.5 0 11-11 0 5.5 5.5 0 0111 0z" />
                </svg>
                <span class="font-semibold">{{ $djsession->current_users }} Participantes</span>
            </div>

            <!-- Exit Button with Improved Design -->
            <button
                wire:click="toggleStatus"
                class="transition-colors font-bold text-sm px-4 py-2 rounded-lg flex items-center space-x-2 group
                    {{ $djsession->active ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}"
            >
                <span>{{ $djsession->active ? 'Desactivar' : 'Activar' }}</span>
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L11 12.586V5a1 1 0 10-2 0v7.586l-2.293-2.293a1 1 0 00-1.414 1.414l4 4z" clip-rule="evenodd" />
                </svg>
            </button>

        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mt-8 border-b border-gray-200" x-data="{ activeTab: 'summary', loaded:{summary: true, songs: false, tips: false, raffless: false} }">
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
            <x-tab-button tab="raffles" color="pink" x-on:click="activeTab = 'raffles'; loaded.raffles = true;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                Sorteos
            </x-tab-button>
        </nav>

        <!-- Content Area (Summary - Song Requests - Tips - Raffles) -->
        <div x-show="activeTab === 'summary'" x-cloak>
            <livewire:djsession-summary :djsession="$djsession"/>
        </div>

        <div x-show="activeTab === 'songs'" x-cloak>
            <livewire:song-requests :djsessionId="$djsession->id" lazy/>
        </div>

        <div x-show="activeTab === 'tips'" x-cloak>
            <livewire:top-donors :djsessionId="$djsession->id" :viewType="'management'" :key="'tips-management'"  lazy/>
            <livewire:tips-list :djsessionId="$djsession->id" lazy/>
        </div>

        <div x-show="activeTab === 'raffles'" x-cloak>
            <livewire:raffles-management :djsessionId="$djsession->id"  wire:lazy :key="'raffles-management-for-session-'.$djsession->id" lazy/>
        </div>
    </div>

    <x-confirmation-modal wire:model="confirmingSessionDeletion" :key="'confirm-session-deletion'">
        <x-slot name="title">Eliminar sesión</x-slot>

        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar esta sesión? Esta acción no se puede deshacer.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingSessionDeletion', false)">
                Cancelar
            </x-secondary-button>

            <form action="{{ route('djsessions.destroy', $djsession) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Eliminar</x-danger-button>
            </form>

        </x-slot>
    </x-confirmation-modal>
</div>


