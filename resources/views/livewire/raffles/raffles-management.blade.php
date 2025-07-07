<div>
    <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 transition-colors duration-200">
        <h2 class="text-xl font-extrabold">Gestión de sorteos</h2>
        <button wire:click="editRaffle(null)" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
            Crear Sorteo
        </button>
    </div>

    @if($currentRaffle)
        <div class="mt-4 bg-white p-6 rounded-lg shadow-md relative ">
            {{-- Menú de opciones (considera moverlo a una esquina, ej. con absolute top-2 right-2) --}}
            <div class="absolute top-4 right-4 flex items-center gap-2">
                {{-- Botón menú (dropdown) --}}
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <button class="p-2 rounded-full hover:bg-gray-200 transition">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 10a2 2 0 114.001.001A2 2 0 016 10zm4-4a2 2 0 110-4 2 2 0 010 4zm0 12a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link wire:click="editRaffle({{ $currentRaffle->id }})" class="cursor-pointer">
                            Editar sorteo
                        </x-dropdown-link>
                        <x-dropdown-button wire:click="confirmDeleteRaffle({{ $currentRaffle->id }})" class="text-red-600 dark:text-red-400">
                            Eliminar sorteo
                        </x-dropdown-button>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Título del sorteo --}}


            {{-- Componente de información del sorteo --}}
            <livewire:raffle-info :djsessionId="$djsessionId" :viewType="'management'" :key="'raffle-management-'.$currentRaffle->id"/>

            {{-- Botones de acción del sorteo --}}
            <div class="mt-4 flex gap-4">
                @if($currentRaffle->isOpen())
                    <x-button wire:click="performRaffleAction('close')" class="bg-red-500 text-black font-bold hover:bg-red-700">
                        Cerrar Participaciones
                    </x-button>
                @elseif($currentRaffle->isClosed())
                    <x-button wire:click="performRaffleAction('open')" class="bg-pink-500 text-black font-bold hover:bg-pink-700">
                        Reabrir Sorteo
                    </x-button>
                    @if($currentRaffle->participants_count > 0)
                        <x-button @click="$dispatch('raffle-draw')" class="bg-yellow-400 text-black font-bold hover:bg-yellow-600" >
                            Sortear
                        </x-button>
                    @endif
                    <x-button wire:click="performRaffleAction('terminate')" class="bg-gray-400 text-black font-bold hover:bg-gray-600">
                        Finalizar sorteo
                    </x-button>
                @elseif($currentRaffle->isDraft() || $currentRaffle->status === 'terminated') {{-- Suponiendo un estado 'pendiente' --}}
                    <x-button wire:click="performRaffleAction('open')" class="bg-pink-500 text-black font-bold hover:bg-pink-700">
                        Abrir sorteo
                    </x-button>
                @endif
            </div>
        </div>

        {{-- Contenedor de la ruleta (se muestra al despachar el evento 'raffle-draw') --}}
        <div x-data="{ show: false }"
            x-on:raffle-draw.window="show = true"
            x-show="show"
            x-on:close-roulette.window="show = false"
            x-on:start-raffle-draw.window="$wire.performRaffleAction('draw')"
            x-cloak
        >
            {{-- El componente de la ruleta ahora se gestiona a sí mismo --}}
            <x-roulette-dj/>
        </div>
    @endif

    <div class="mt-4">
        @if($raffles->isEmpty())
            <p class="text-gray-500">No hay sorteos disponibles.</p>
        @else
            <ul class="space-y-4">
                @foreach($raffles as $raffle)
                    <li class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex items-start gap-4">
        {{-- Imagen del premio --}}
        <div class="flex-shrink-0">
            <div class="w-20 h-20 rounded-lg overflow-hidden shadow-md border-2 border-white">
                <img src="{{ $raffle->prize_image_url }}" alt="{{ $raffle->prize_name }}" class="object-cover w-full h-full">
            </div>
        </div>

        {{-- Texto central (nombre y descripción) --}}
        <div class="flex-1">
            <h3 class="text-lg font-bold">{{ $raffle->prize_name }}</h3>
            <p class="text-gray-600 mt-1">{{ $raffle->description }}</p>
        </div>

        {{-- Indicadores a la derecha --}}
        <div class="flex flex-col items-end gap-2">
            <div class="flex items-center gap-2">
                {{-- Indicador de estado --}}
                @if($raffle->isOpen())
                    <div class="inline-flex items-center bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
                        <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                        ABIERTO
                    </div>
                @elseif($raffle->isClosed())
                    <div class="inline-flex items-center bg-yellow-400 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
                        <div class="w-2 h-2 bg-white rounded-full mr-2"></div>
                        CERRADO
                    </div>
                @else
                    <div class="inline-flex items-center bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
                        <div class="w-2 h-2 bg-white rounded-full mr-2"></div>
                        FINALIZADO
                    </div>
                @endif

                {{-- Botón de menú --}}
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <button class="p-2 rounded-full hover:bg-gray-200 transition">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 10a2 2 0 114.001.001A2 2 0 016 10zm4-4a2 2 0 110-4 2 2 0 010 4zm0 12a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-button wire:click="confirmSetCurrentRaffle({{ $raffle->id }})" class="text-pink-600 dark:text-pink-400">
                            Establecer como actual
                        </x-dropdown-button>
                        <x-dropdown-link wire:click="editRaffle({{  $raffle->id }})">
                            Editar sorteo
                        </x-dropdown-link>
                        <x-dropdown-button wire:click="confirmDeleteRaffle({{ $raffle->id }})" class="text-red-600 dark:text-red-400">
                            Eliminar sorteo
                        </x-dropdown-button>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Participantes --}}
            <div class="flex items-center gap-2">
                <div class="text-2xl font-bold text-blue-600">{{ $raffle->participants_count }}</div>
                <p class="text-sm text-blue-700 font-medium">Participantes 👥</p>
            </div>
        </div>
    </div>
</li>


                @endforeach
            </ul>
        @endif
    </div>

    <!-- Modal Create/Edit Raffle -->
    <div
        x-data="{ show: @entangle('isRaffleFormVisible'), visible: false }"
        x-init="$watch('show', value => {
            if (value) {
                document.body.classList.add('cursor-wait');
                // Si es para mostrar, esperamos a que termine la animación
                setTimeout(() => {
                    visible = true;
                    document.body.classList.remove('cursor-wait');
                }, 450);
            } else {
                visible = false; // Si es para ocultar, hacemos invisible el modal inmediatamente
            }
        })"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-show="visible"
        class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center px-4"
        style="display: none;"
        :key="'raffle-form-modal'"
    >
        <livewire:raffle-manager-form :djsession-id="$djsessionId" />
    </div>

    <x-confirmation-modal wire:model="confirmingRaffleAction" :key="'confirm-raffle-action'">
        <x-slot name="title">{{ $raffleActionToConfirm['title'] }}</x-slot>

        <x-slot name="content">
            {{ $raffleActionToConfirm['description'] }}
        </x-slot>

        <x-slot name="footer">
            {{-- Ahora puedes volver a usar wire:click de forma segura y sencilla --}}
            <x-secondary-button wire:click="resetRaffleActionToConfirm">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="executeRaffleAction" class="bg-green-600 text-white hover:bg-green-700">
                {{ $raffleActionToConfirm['action'] }}
            </x-button>
        </x-slot>
    </x-confirmation-modal>

</div>
