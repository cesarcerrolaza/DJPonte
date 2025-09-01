<aside 
    class="fixed inset-y-0 left-0 bg-[#222222] text-white w-72 flex flex-col z-40
           transform transition-transform duration-300 ease-in-out"
    :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }"
    x-cloak
>
    <div class="bg-[#b481ff] h-16 md:h-20 flex items-center justify-center flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('storage/icons/djponte-logo.svg') }}" alt="DJ-PONTE" class="h-10 md:h-12 lg:h-14">
        </a>
    </div>

    <div class="flex-1 flex flex-col p-4 space-y-6 overflow-y-auto">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center overflow-hidden">
                @if(Auth::check() && Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl font-bold">{{ Auth::user() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'DJ' }}</span>
                @endif
            </div>
            <div>
                <p class="font-semibold">{{ Auth::user()->name ?? 'DJ Name' }}</p>
                <p class="text-gray-400 text-sm">{{ Auth::user()->username ? '@' . Auth::user()->username : '' }}</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span>Inicio</span>
            </a>
            
            <a href="{{ route('djsessions.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                <span>Mis Sesiones</span>
            </a>
            
            <a href="{{ route('social.management') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 transition-colors">
                {{-- Icono de instagram --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 011.998.562m-3.213 1.409a4 4 0 013.213-1.409M12 7a5 5 0 110 10 5 5 0 010-10zm0 0V6m0 1v1m0-1h1m-1 0H11" /></svg>
                <span>Redes Sociales</span>
            </a>
            
            <a href="{{ route('settings') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span>Configuración</span>
            </a>
        </nav>

        
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <a href="{{ route('logout') }}" @click.prevent="$root.submit()" class="flex items-center space-x-3 p-3 rounded-lg text-red-400 hover:bg-red-500 hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span>{{ __('Cerrar sesión') }}</span>
                </a>
            </form>
        </div>
    </div>
</aside>
