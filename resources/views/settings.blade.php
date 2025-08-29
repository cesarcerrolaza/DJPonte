@extends('layouts.app')

@section('content')
    <div>
        {{-- Establecemos un contenedor con padding y un ancho máximo para que no ocupe toda la pantalla --}}
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- 1. Incluir el formulario de "Actualizar Información del Perfil" de Jetstream --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                {{-- Componente de Jetstream para la línea separadora --}}
                <x-section-border />
            @endif

            {{-- 2. Incluir el formulario de "Actualizar Contraseña" de Jetstream --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('change-user-role-form')
            </div>
            <x-section-border />
            
            {{-- 3. (Opcional) Incluir el formulario de Autenticación de Dos Factores --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            {{-- 4. (Opcional) Incluir el formulario de "Cerrar Otras Sesiones" --}}
            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- 5. (Opcional) Incluir el formulario de "Eliminar Cuenta" --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif

        </div>
    </div>
@endsection