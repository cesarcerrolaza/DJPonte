<!-- Modal de registro -->
<div
    class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center px-4"
    x-show="showRegisterModal"
    x-cloak
    @keydown.escape.window="closeModals()"
>
    <div class="bg-black text-white rounded-2xl shadow-xl px-6 py-6 w-full max-w-sm relative">
        
        <!-- Botón de cerrar -->
        <button @click="closeModals()" class="absolute top-3 left-4 text-white text-xl">&times;</button>

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('storage/icons/djponte-logo.svg') }}" class="h-10 md:h-12" alt="DJ-PONTE">
        </div>

        <!-- Título -->
        <h2 class="text-2xl font-bold mb-6 text-center">Crea tu cuenta</h2>

        <!-- Errores -->
        <x-validation-errors class="mb-4" />

        <!-- Formulario -->
        <form method="POST" action="{{ route('register') }}" onsubmit="localStorage.clear();">
            @csrf

            <!-- Nombre -->
            <div class="mb-4">
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white"
                    placeholder="Nombre">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white"
                    placeholder="Correo electrónico">
            </div>

            <!-- Rol -->
            <div class="mb-4">
                <select name="role" required
                    class="w-full rounded-md px-4 py-2 bg-black border border-gray-500 text-white focus:outline-none">
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                    <option value="dj" {{ old('role') == 'dj' ? 'selected' : '' }}>DJ</option>
                </select>
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <input type="password" name="password" required placeholder="Contraseña"
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white">
            </div>

            <!-- Confirmar contraseña -->
            <div class="mb-4">
                <input type="password" name="password_confirmation" required placeholder="Confirmar contraseña"
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white">
            </div>

            <!-- Términos -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-4 text-xs text-gray-400">
                    <label class="flex items-center space-x-2">
                        <x-checkbox name="terms" required />
                        <span>
                            {!! __('Estoy de acuerdo con los :terms_of_service y la :privacy_policy', [
                                'terms_of_service' => '<a href="'.route('terms.show').'" class="underline">condiciones de uso</a>',
                                'privacy_policy' => '<a href="'.route('policy.show').'" class="underline">política de privacidad</a>',
                            ]) !!}
                        </span>
                    </label>
                </div>
            @endif

            <!-- Botón -->
            <button type="submit"
                class="w-full py-2 mt-2 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition">
                Siguiente
            </button>
        </form>
    </div>
</div>
