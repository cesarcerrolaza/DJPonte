<div class="bg-white p-4 rounded-lg shadow-md w-full max-w-5xl mx-auto mt-2 border-t-4 border-yellow-400">
    <h3 class="text-lg font-bold mb-3">Enviar una propina</h3>
    
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    
    <form 
        wire:submit.prevent="submit" 
        class="space-y-4"
    >
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                Cantidad (€)
            </label>
            <input 
                wire:model="amount" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                id="amount" 
                type="number"
                step="0.50"
                min="0.50"
                placeholder="5.00"
            >
            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Descripción
            </label>
            <textarea 
                wire:model="description" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                id="description" 
                placeholder="Mensaje opcional para el dj"
                rows="2"
            ></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="relative">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="songName">
                Petición de canción
            </label>
            <input 
                wire:model.live.debounce.300ms="songName"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                id="songName" 
                type="text" 
                placeholder="Título de la canción o artista"
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
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded"
                    x-on:click="$dispatch('show-global-loader')"
                >
                    Enviar Propina
                </button>
            </div>
        </div>
    </form>
    <!-- Ranking de propinas -->
    <livewire:top-donors :djsessionId="$djsession->id" :viewType="'form'" :key="'form'" />
    
    <!-- Última propina recibida -->
     @if(isset($lastTip) && !empty($lastTip))
        <x-tip-item :tipData="$lastTip" />
    @endif
</div>