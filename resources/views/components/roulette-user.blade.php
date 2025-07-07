<style>
    @keyframes infinite-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-infinite-spin {
        animation: infinite-spin 2s linear infinite;
    }
</style>

@php
    // Variables para el tamaño, igual que en el otro componente
    $rouletteSize = 'w-[500px] h-[500px]';
    $stylusHeight = 'h-[520px]';
@endphp

<div
    x-data="{
        showModal: false,
        isSpinning: false,
        showWinnerCard: false,
        winnerData: null,

        // Método principal que se activa con el evento
        startRaffle(event) {
            // 1. Recibe los datos del ganador del evento.
            this.winnerData = event.detail.winner;
            
            // 2. Muestra el modal y empieza a girar la ruleta.
            this.showModal = true;
            this.isSpinning = true;

            // 3. Simula el giro durante 5 segundos.
            setTimeout(() => {
                // 4. Detiene el giro y muestra la tarjeta del ganador.
                this.isSpinning = false;
                this.showWinnerCard = true;
            }, 5000); // 5 segundos de giro
        },

        // Resetea y cierra el modal
        closeModal() {
            this.showModal = false;
            this.showWinnerCard = false;
            this.winnerData = null;
        }
    }"
    x-on:raffle-winner.window="startRaffle($event)"
    x-show="showModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm"
>
    {{-- Botón para cerrar el modal --}}
    <button @click="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-40">
        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>

    <div class="relative {{ $rouletteSize }}">
        {{-- Indicador (aguja) --}}
        <div class="absolute z-10 top-1/2 -translate-y-1/2 right-0.5 rotate-[+15deg]">
            <img src="{{ asset('storage/icons/stylus.svg') }}" class="{{ $stylusHeight }} w-auto drop-shadow-lg" alt="Indicador">
        </div>

        {{-- Ruleta --}}
        <div class="w-full h-full" :class="{ 'animate-infinite-spin': isSpinning }">
            <img src="{{ asset('storage/icons/wheel.svg') }}" alt="Ruleta" class="w-full h-full">
        </div>
    </div>

    {{-- Módulo emergente para mostrar al ganador --}}
    <div
        x-show="showWinnerCard"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute bottom-10 z-30 flex flex-col items-center p-6 bg-white rounded-2xl shadow-2xl"
    >
        <img src="{{ asset('storage/icons/winner.svg') }}" alt="Icono de Ganador" class="w-128 h-128 mb-4">
        <p class="text-gray-500 text-lg font-semibold">El ganador es...</p>
        <h2 x-text="winnerData ? winnerData.name : ''" class="text-4xl font-bold text-gray-800 mt-2"></h2>
    </div>
</div>