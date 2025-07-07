<div class="bg-white p-4 rounded-lg shadow-md w-full max-w-5xl mx-auto mt-2 border-t-4 border-pink-500"
    x-data="{ canParticipate: @entangle('canParticipate') }"
>
    @if ($raffle != null)
        <livewire:raffle-info :djsessionId="$djsessionId" :viewType="'form'" :key="'raffle-info-'.$djsessionId"/>
        <h3 class="text-lg font-bold mb-3">Participa en el sorteo</h3>
        <button
            wire:click="participate"
            class="font-bold text-sm px-4 py-2 rounded flex items-center"
            :class="{
                'bg-gray-400 text-black cursor-not-allowed opacity-50 ': !canParticipate,
                'text-white bg-pink-500 hover:bg-pink-600': canParticipate,
            }"
            :disabled="!canParticipate"
            wire:loading.attr="disabled"
            wire:target="participate"
        >
            <span wire:loading.remove wire:target="participate">Pulsa para participar</span>
            <span wire:loading wire:target="participate" class="opacity-75 cursor-wait">Procesando...</span>
        </button>
    @else
        <p class="text-gray-500">No hay sorteos disponibles en este momento.</p>
    @endif
</div>