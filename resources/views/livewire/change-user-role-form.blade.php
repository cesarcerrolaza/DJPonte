<x-action-section>
    <x-slot name="title">
        {{ __('Tipo de Cuenta') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Cambia entre una cuenta de usuario estándar y una cuenta de DJ para recibir propinas y gestionar eventos.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            @if (Auth::user()->role === 'dj')
                <p>Actualmente tienes una cuenta de <span class="font-bold text-indigo-400">DJ</span>. Puedes gestionar sesiones, recibir propinas y crear sorteos.</p>
            @else
                <p>Actualmente tienes una cuenta de <span class="font-bold text-green-400">Usuario</span>. Puedes participar en eventos, pedir canciones y dar propinas.</p>
            @endif
        </div>

        <div class="mt-5">
            @if (Auth::user()->role === 'dj')
                {{-- Este botón ahora abre el modal de confirmación --}}
                <x-danger-button wire:click="changeRole" wire:loading.attr="disabled">
                    {{ __('Cambiar a Cuenta de Usuario') }}
                </x-danger-button>
            @else
                <x-button wire:click="confirmChangeRole" wire:loading.attr="disabled">
                    {{ __('Cambiar a Cuenta de DJ') }}
                </x-button>
            @endif
        </div>

        <x-confirmation-modal wire:model.live="confirmingRoleChange">
            <x-slot name="title">
                {{ __('Cambiar a Cuenta de Usuario') }}
            </x-slot>

            <x-slot name="content">
                <p class="font-bold text-red-500">¡Atención! Esta acción es irreversible.</p>
                <p class="mt-2">{{ $alertFirst}}</p>
                <p class="mt-2">{{ $alertSecond }}</p>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingRoleChange', false)" wire:loading.attr="disabled">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                {{-- Este botón ejecuta la acción destructiva --}}
                <x-danger-button class="ml-3" wire:click="changeRole" wire:loading.attr="disabled">
                    {{ __('Sí, estoy seguro') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>

        @if (session('status'))
            <p class="mt-3 text-sm font-medium text-green-600 dark:text-green-400">
                {{ session('status') }}
            </p>
        @endif
    </x-slot>
</x-action-section>
