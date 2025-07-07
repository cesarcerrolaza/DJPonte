{{-- Coloca esto al principio de tu archivo roulette.blade.php --}}
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
    $rouletteSize = 'w-[500px] h-[500px]';
    $stylusHeight = 'h-[520px]';
@endphp

<div
    x-data="{
        // Estados de control
        isSpinning: false,
        winnerData: null,
        showWinner: false,
        
        // Banderas de sincronización
        timerFinished: false,
        winnerReceived: false,

        // Inicia todo el proceso
        startProcess() {
            if (this.isSpinning) return;
            this.isSpinning = true;
            this.$dispatch('start-raffle-draw');
            setTimeout(() => {
                this.timerFinished = true;
                if (this.winnerReceived) {
                    this.stopSpin();
                }
            }, 5000);
        },

        // Se ejecuta cuando se recibe el evento 'raffle-winner'
        handleWinner(event) {
            this.winnerData = event.detail.winner;
            this.winnerReceived = true;
            if (this.timerFinished) {
                this.stopSpin();
            }
        },

        // Para la ruleta y muestra al ganador
        stopSpin() {
            this.isSpinning = false; 
            this.showWinner = true;
        },

        // --- ¡NUEVO MÉTODO! ---
        // Resetea el estado para permitir un nuevo sorteo
        resetRaffle() {
            this.showWinner = false;    // Oculta el mensaje del ganador
            this.winnerData = null;     // Limpia los datos del ganador
            this.timerFinished = false; // Resetea la bandera del temporizador
            this.winnerReceived = false;// Resetea la bandera del ganador
        }
    }"
    x-on:raffle-winner.window="handleWinner($event)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm"
    x-cloak
>
    {{-- Botón para cerrar el modal --}}
    <button @click="$dispatch('close-roulette')" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-40">
        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>

    {{-- Contenedor del juego de la ruleta --}}
    <div class="relative {{ $rouletteSize }}">

        <div x-show="!isSpinning && !winnerData" @click="startProcess()" x-transition class="absolute inset-0 z-20 flex items-center justify-center bg-black/50 rounded-full cursor-pointer">
            <span class="text-white text-3xl font-bold text-center select-none animate-pulse">Girar</span>
        </div>

        {{-- Indicador (aguja) --}}
        <div class="absolute z-10 top-1/2 -translate-y-1/2 right-0.5 rotate-[+15deg]">
            <img src="{{ asset('storage/icons/stylus.svg') }}" class="{{ $stylusHeight }} w-auto drop-shadow-lg" alt="Indicador">
        </div>

        {{-- Ruleta --}}
        <div x-ref="ruleta" class="w-full h-full" :class="{ 'animate-infinite-spin': isSpinning }">
            <img src="{{ asset('storage/icons/wheel.svg') }}" alt="Ruleta" class="w-full h-full">
        </div>
    </div>

    {{-- Módulo emergente para mostrar al ganador --}}
    <div
        x-show="showWinner"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full"
        class="absolute bottom-10 z-30 flex flex-col items-center p-6 bg-white rounded-2xl shadow-2xl"
    >
        <img src="{{ asset('storage/icons/winner.svg') }}" alt="Icono de Ganador" class="w-128 h-128 mb-4">
        <p class="text-gray-500 text-lg font-semibold">El ganador es...</p>
        <h2 x-text="winnerData ? winnerData.name : ''" class="text-4xl font-bold text-gray-800 mt-2"></h2>
        
        {{-- Botón para resetear y volver a girar --}}
        <button
            @click="resetRaffle()"
            class="mt-6 p-2 rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition"
            aria-label="Sortear de nuevo"
        >
            <svg class="h-8 w-8"  xmlns="http://www.w3.org/2000/svg">
                <g id="Group_8" data-name="Group 8">
                    <path id="Path_33" data-name="Path 33" d="M20.587,14.613,18,17.246V9.98A1.979,1.979,0,0,0,16.02,8h-.04A1.979,1.979,0,0,0,14,9.98v6.963l-.26-.042-2.248-2.227a2.091,2.091,0,0,0-2.657-.293A1.973,1.973,0,0,0,8.58,17.4l6.074,6.016a2.017,2.017,0,0,0,2.833,0l5.934-6a1.97,1.97,0,0,0,0-2.806A2.016,2.016,0,0,0,20.587,14.613Z" fill="#040405"/>
                    <path id="Path_34" data-name="Path 34" d="M16,0A16,16,0,1,0,32,16,16,16,0,0,0,16,0Zm0,28A12,12,0,1,1,28,16,12.013,12.013,0,0,1,16,28Z" fill="#040405"/>
                </g>
            </svg>
        </button>

    </div>
</div>