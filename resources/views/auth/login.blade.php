<!-- Modal login -->
<div
    class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center px-4"
    x-show="showLoginModal"
    x-cloak
    @keydown.escape.window="closeModals()"
>
    <div class="bg-black text-white rounded-2xl shadow-xl p-6 w-full max-w-sm relative">

        <!-- Botón de cerrar -->
        <button @click="closeModals()" class="absolute top-3 left-4 text-white text-xl">&times;</button>

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/icons/djponte-logo.svg') }}" class="h-10 md:h-12" alt="DJ-PONTE">
        </div>

        <!-- Errores -->
        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-500">
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" onsubmit="localStorage.clear();">
            @csrf

            <!-- Email o nombre -->
            <div class="mb-4">
                <input id="email" name="email" type="email" required autofocus
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    placeholder="Teléfono, correo electrónico o nombre"
                    value="{{ old('email') }}">
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg px-4 py-2 bg-black border border-gray-500 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    placeholder="Contraseña">
            </div>

            <!-- Botón login -->
            <button type="submit"
                class="w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-full transition-colors">
                Iniciar Sesión
            </button>

            <!-- ¿Olvidaste tu contraseña? -->
            @if (Route::has('password.request'))
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-white hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            @endif

            <!-- Enlace a registro -->
            <div class="text-center mt-4 text-sm text-gray-500">
                ¿No tienes una cuenta?
                <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Regístrate</a>
            </div>
        </form>
    </div>
</div>
