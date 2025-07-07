<div class="bg-white p-4 rounded-lg shadow-md w-full max-w-5xl mx-auto mt-2 border-t-4 border-purple-500"
    x-data="{
            processing: false,
            userVotes: [],
            timeout: { active: false, remaining: 0 },

            init() {
                // Cargar votos guardados
                const storedVotes = localStorage.getItem('userVotes');
                if (storedVotes) {
                    this.userVotes = JSON.parse(storedVotes);
                }

                // Por ejemplo, si Livewire emitió un evento al hacer una petición
                window.addEventListener('song-requested', (e) => {
                    this.processing = false;
                    this.timeout.active = true;
                    this.timeout.remaining = e.detail.timeout ?? 60; // o lo que venga
                    this.startCountdown();
                });

                // Cargar countdown si ya había uno activo al iniciar
                if (this.$wire.userLastRequestAt && this.$wire.songRequestTimeout > 0) {
                    const remaining = Math.max(
                        0,
                        this.$wire.songRequestTimeout - Math.floor((Date.now() - new Date(this.$wire.userLastRequestAt).getTime()) / 1000)
                    );
                    if (remaining > 0) {
                        this.timeout.active = true;
                        this.timeout.remaining = remaining;
                        this.startCountdown();
                    }
                }
            },

            startCountdown() {
                if (this.timeout.active && this.timeout.remaining > 0) {
                    const timer = setInterval(() => {
                        this.timeout.remaining--;
                        if (this.timeout.remaining <= 0) {
                            this.timeout.active = false;
                            clearInterval(timer);
                            this.$dispatch('timeout-ended');
                        }
                    }, 1000);
                }
            },

            formatTime() {
                const minutes = Math.floor(this.timeout.remaining / 60);
                const seconds = this.timeout.remaining % 60;
                return `${minutes}:${seconds.toString().padStart(2, '0')}`;
            },

            waiting() {
                return this.timeout.active;
            },

            addVote(songId) {
                if (!this.userVotes.includes(songId)) {
                    this.userVotes.push(songId);
                    localStorage.setItem('userVotes', JSON.stringify(this.userVotes));
                }
            }
        }"
    x-init="init()"
>
    <h3 class="text-lg font-bold mb-3">Solicitar una canción</h3>
    
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    
    <!-- Contador de tiempo global -->
    <div 
        x-show="waiting()" 
        class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700"
    >
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            <span>Podrás realizar solicitudes en <strong x-text="formatTime()"></strong></span>
        </div>
    </div>
    
    <form 
        wire:submit.prevent="submitRequest" 
        class="space-y-4"
    >
        <div class="relative">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="songName">
                Nombre de la canción
            </label>
            <input 
                wire:model.live.debounce.300ms="songName"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                id="songName" 
                type="text" 
                placeholder="Título de la canción"
                autocomplete="off"
            >
            @error('songName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            
            <!-- Lista de sugerencias -->
            @if (!empty($songSuggestions))
                <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                    <ul class="max-h-60 overflow-auto">
                        @foreach ($songSuggestions as $song)
                            <li 
                                wire:click="selectSong({{ $song['id'] }})" 
                                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                            >
                                <div class="font-medium">{{ $song['title'] }}</div>
                                <div class="text-xs text-gray-500">{{ $song['artist'] }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="artistName">
                Artista
            </label>
            <input 
                wire:model="artistName" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                id="artistName" 
                type="text" 
                placeholder="Nombre del artista"
            >
            @error('artistName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex items-center justify-between">
            <!-- Botones -->
            <div class="flex items-center">
                <button 
                    type="button"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
                    x-on:click="
                        if(!waiting()) { 
                            processing = true; 
                        }"
                    :disabled="waiting()"
                    :class="{
                        'opacity-50 cursor-not-allowed': waiting(),
                        'opacity-50 cursor-wait': processing
                        }"

                >
                    Enviar Solicitud
                </button>
            </div>
        </div>
    </form>
    
    <!-- Ranking de canciones -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Top 5 Canciones Solicitadas</h3>
        
        @if(count($topSongs) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($topSongs as $song)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $song['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $song['artist'] }}</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600 mr-3">{{ $song['score'] }} votos</span>
                            <button 
                                wire:click="voteSong({{ $song['id'] }})" 
                                x-on:click="
                                if(!userVotes.includes({{ $song['id'] }}) && !waiting()) { 
                                    processing = true;
                                }"
                                x-init="$wire.on('voted-successfully.{{ $song['id'] }}', () => {
                                            addVote({{ $song['id'] }});
                                            processing = false;
                                        });"
                                class="p-2 rounded-full transition-colors duration-300"
                                :class="{
                                    'bg-indigo-600 text-white': userVotes.includes({{ $song['id'] }}),
                                    'bg-gray-100 hover:bg-gray-200 text-gray-600': !userVotes.includes({{ $song['id'] }}) && !waiting(),
                                    'opacity-75 cursor-wait': processing,
                                    'bg-gray-300 text-gray-500 cursor-not-allowed': waiting()
                                }"
                                :disabled="userVotes.includes({{ $song['id'] }}) || processing || waiting()"
                                title="Votar por esta canción"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" x-bind:class="{ 'animate-pulse': processing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        x-bind:d="userVotes.includes({{ $song['id'] }}) 
                                            ? 'M5 13l4 4L19 7' 
                                            : 'M12 4v16m8-8H4'" />
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center py-4">No hay solicitudes de canciones todavía.</p>
        @endif
    </div>
</div>
