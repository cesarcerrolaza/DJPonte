<!-- Contenido del formulario -->
<div class="w-full max-w-md mx-auto px-6 py-12">
    <div x-data="{ showExternalOptions: false }">
        <!-- Botón de Iniciar Sesión -->
        <div class="mb-6">
            <button @click="openLoginModal()" class="w-full py-3 px-4 bg-yellow-400 hover:bg-yellow-500 text-black rounded-full font-bold transition-colors">
                Iniciar Sesión
            </button>

        </div>

        <!-- Enlace para olvidar contraseña -->
        <div class="text-center mb-4">
            <a href="#" class="text-blue-500 hover:underline text-sm">
                ¿Has olvidado tu contraseña?
            </a>
        </div>

        <!-- Acordeón de registro -->
        <div class="mb-8">
            <button 
                @click="showExternalOptions = !showExternalOptions" 
                class="w-full flex justify-center items-center text-black font-semibold"
            >
                ¿No tienes cuenta?
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 ml-1 transition-transform" 
                    :class="showExternalOptions ? 'transform rotate-180' : ''"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Opciones de servicios externos expandibles -->
            <div 
                x-show="showExternalOptions"
                x-cloak
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 transform -translate-y-2" 
                x-transition:enter-end="opacity-100 transform translate-y-0" 
                x-transition:leave="transition ease-in duration-150" 
                x-transition:leave-start="opacity-100 transform translate-y-0" 
                x-transition:leave-end="opacity-0 transform -translate-y-2" 
                class="mt-4 space-y-4"
            >

                <!-- Separador -->
                <div class="relative flex items-center my-6">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">o</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Continuar con servicios -->
                <a href="#" class="block w-full py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 text-center rounded-lg font-medium">
                    Continuar con Instagram
                </a>
                <a href="#" class="block w-full py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 text-center rounded-lg font-medium">
                    Continuar con TikTok
                </a>
                <a href="#" class="block w-full py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 text-center rounded-lg font-medium">
                    Continuar con Google
                </a>
            </div>
        </div>
        <div class="mt-4 space-y-4">
        <!-- Botón crear cuenta -->
            <div class="mt-4 space-y-4">
                <button @click="openRegisterModal()" class="block w-full py-3 bg-purple-600 hover:bg-purple-700 text-white text-center rounded-lg font-medium">
                    Crear cuenta
                </button>
                <a href="#" class="block w-full py-3 bg-pink-500 hover:bg-pink-600 text-white text-center rounded-lg font-medium">
                    Continuar como invitado
                </a>
            </div>
            <!-- Términos y condiciones -->
            <div class="text-sm text-gray-600 text-center">
                Al continuar, confirmas que estás de acuerdo con los 
                <a href="#" class="text-black underline">Términos de uso</a> de DJ-PONTE y que has leído la 
                <a href="#" class="text-black underline">Política de privacidad</a> de DJ-PONTE.
            </div>
        </div>
    </div>
</div>