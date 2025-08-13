@extends('layouts.landing')
@section('content')

<div x-data="authModals()" x-init="initRoute(); setupPopStateHandler()">
    {{-- Hero Section --}}
    <div class="relative min-h-screen flex items-center justify-center text-white overflow-hidden">
        {{-- Fondo con imagen y superposición oscura --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/general/dj-background.jpg') }}" alt="DJ mixing music" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black opacity-60"></div>
        </div>

        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight mb-4 animate-fade-in-down">
                <img src="{{ asset('storage/icons/djponte-logo.svg') }}" alt="DJPonte Logo" class="h-64 w-64 mx-auto block">
            </h1>
            <p class="text-lg md:text-2xl font-light max-w-3xl mx-auto mb-8 animate-fade-in-up" style="animation-delay: 0.5s;">
                Peticiones de Canciones, Sorteos y Propinas. La conexión definitiva con tu público.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 1s;">
                <button @click="openRegisterModal()" class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">
                    Crear Cuenta
                </button>
                <button @click="openLoginModal()" class="w-full sm:w-auto bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                    Iniciar Sesión
                </button>
            </div>
             <a href="#features" class="inline-block mt-8 text-white font-semibold animate-fade-in-up" style="animation-delay: 1.2s;">
                Saber más <span class="ml-1">&darr;</span>
            </a>
        </div>
    </div>

    <div x-show="showLoginModal" x-cloak>
        @include('auth.login')
    </div>

    <div x-show="showRegisterModal" x-cloak>
        @include('auth.register')
    </div>

    {{-- Sección de Características --}}
    <section id="features" class="bg-gray-900 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold">Potencia tus Sesiones</h2>
                <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Todo lo que necesitas para que tu evento sea interactivo e inolvidable.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Característica 1 --}}
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="text-indigo-400 mb-4">

                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>music [#1005]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-260.000000, -3759.000000)" fill="#818cf8"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M224,3601.05129 L224,3610.55901 C224,3612.90979 222.17612,3614.95492 219.888035,3614.89646 C217.266519,3614.82877 215.248971,3612.1662 216.234285,3609.31593 C216.777356,3607.74464 218.297755,3606.71797 219.920978,3606.69233 C220.695653,3606.68105 220.976173,3606.88208 222.003416,3607.24105 L222.003416,3604.12822 C222.003416,3603.56207 221.556181,3603.10258 221.005124,3603.10258 L213.018786,3603.10258 C212.467729,3603.10258 212.020494,3603.56207 212.020494,3604.12822 L212.020494,3614.65851 C212.020494,3617.02057 210.179644,3619.07289 207.881575,3618.99801 C205.681339,3618.92622 203.914362,3617.02775 204.00321,3614.73031 C204.090061,3612.51594 205.989811,3610.84209 208.147121,3610.79081 C209.166377,3610.76619 209.352059,3610.92619 210.02391,3611.34363 L210.02391,3601.05129 C210.02391,3599.91795 210.91838,3599 212.020494,3599 L222.003416,3599 C223.106529,3599 224,3599.91795 224,3601.05129" id="music-[#1005]"> </path> </g> </g> </g> </g></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Peticiones Sociales</h3>
                    <p class="text-gray-400">Recibe peticiones de canciones desde los comentarios de tus posts de Instagram o directamente desde la app.</p>
                </div>

                {{-- Característica 2 --}}
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="text-indigo-400 mb-4">
                        <img src="{{ asset('storage/icons/wheel.svg') }}" alt="Ruleta" class="w-12 h-12">
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Sorteos en Vivo</h3>
                    <p class="text-gray-400">Crea sorteos interactivos en tiempo real. Aumenta la participación y premia a tu público con una ruleta animada.</p>
                </div>

                {{-- Característica 3 --}}
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="text-indigo-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Propinas Digitales</h3>
                    <p class="text-gray-400">Ofrece a tu público una forma sencilla y segura de agradecerte tu trabajo a través de propinas.</p>
                </div>

                {{-- Característica 4 --}}
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="text-indigo-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Panel de Control</h3>
                    <p class="text-gray-400">Gestiona todo desde un panel intuitivo y en tiempo real.</p>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- TU SCRIPT DE ALPINE.JS SE QUEDA IGUAL --}}
<script>
    function authModals() {
        return {
            showLoginModal: false,
            showRegisterModal: false,
            initRoute() {
                const path = window.location.pathname;
                if (path === '/login') {
                    this.showLoginModal = true;
                }
                if (path === '/register' || sessionStorage.getItem('fromRegister') === 'true') {
                    sessionStorage.removeItem('fromRegister');
                    this.showRegisterModal = true;
                }
            },
            openLoginModal() {
                this.showLoginModal = true;
                this.showRegisterModal = false; // Aseguramos que el otro modal se cierre
                history.pushState({}, '', '/login');
            },
            openRegisterModal() {
                this.showRegisterModal = true;
                this.showLoginModal = false; // Aseguramos que el otro modal se cierre
                history.pushState({}, '', '/register');
            },
            closeModals() {
                this.showLoginModal = false;
                this.showRegisterModal = false;
                history.pushState({}, '', '/');
            },
            setupPopStateHandler() {
                window.onpopstate = () => {
                    this.initRoute();
                };
            }
        }
    }
</script>

{{-- Estilos para animaciones --}}
<style>
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.8s ease-out forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
</style>

@endsection