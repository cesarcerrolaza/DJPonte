<div class="bg-black text-white rounded-2xl shadow-xl p-6 w-full max-w-md relative">

    <!-- Botón de cerrar -->
    <button type="button" @click="$dispatch('close')" wire:click="$parent.closeRaffleForm()" class="absolute top-3 left-4 text-white text-xl">&times;</button>
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('storage/icons/djponte-logo.svg') }}" class="h-10 md:h-12" alt="DJ-PONTE">
    </div>


    <x-validation-errors class="mb-4" />

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-500">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- Nombre del premio -->
        <div class="mb-4">
            <input wire:model.defer="prize_name" type="text"
                class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Nombre del premio">
        </div>

        <!-- Cantidad -->
        <div class="mb-4">
            <input wire:model.defer="prize_quantity" type="number" min="1"
                class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Cantidad de premios">
        </div>

        <!-- Imagen -->
        <div 
        class="relative w-32 h-32 md:w-36 md:h-36"
            x-data="{
                // Guarda la URL inicial que viene de Livewire
                initialUrl: '{{ $prize_image_url }}',
                
                // Almacenará la URL de la previsualización local
                previewUrl: '',

                // Función que se encarga de actualizar la imagen
                updatePreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            }"
        >
            <img 
                alt="Imagen Sorteo" 
                class="w-full h-full object-cover rounded-xl shadow-lg ring-4 ring-purple-100"
                {{-- El :src hace que la imagen sea reactiva a los datos de Alpine --}}
                :src="previewUrl || initialUrl" 
            >

            <label 
                for="image" 
                class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 bg-purple-600 text-white p-2 rounded-full cursor-pointer hover:bg-purple-700 transition-colors shadow-md"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </label>

            <input 
                type="file" 
                id="image" 
                wire:model.live="prize_image"
                name="image" 
                class="hidden" 
                accept="image/*"
                {{-- Usamos @change de Alpine para llamar a nuestra función --}}
                @change="updatePreview"
            >
        </div>

        <!-- Descripción -->
        <div class="mt-6 mb-4">
            <textarea wire:model.defer="description"
                class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Descripción del sorteo (opcional)"></textarea>
        </div>

        <!-- Botón guardar -->
        <button type="submit"
            class="w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-full transition-colors">
            {{ $raffle ? 'Actualizar' : 'Crear' }} Sorteo
        </button>
    </form>
</div>
