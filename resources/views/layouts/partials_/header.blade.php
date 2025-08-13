<div class="flex w-full h-16 md:h-20 bg-black">
    <nav class="flex-1 flex items-center justify-between px-4 md:px-8 text-white">
        
        <div class="flex items-center">
            <button @click="isSidebarOpen = !isSidebarOpen" class="relative z-50 text-white focus:outline-none p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div class="hidden md:flex items-center space-x-8">
            <a href="{{ route('home') }}" class="p-2 relative group">
                <img src="{{ asset('storage/icons/home.svg') }}" alt="Inicio" class="h-8 w-8 {{ Route::is('home') ? 'hidden' : 'block' }} group-hover:hidden">
                <img src="{{ asset('storage/icons/home-selected.svg') }}" alt="Inicio seleccionado" class="h-9 w-9 {{ Route::is('home') ? 'block' : 'hidden' }} group-hover:block">
            </a>
            <a href="{{ route('djsessions.index') }}" class="p-2 relative group">
                <img src="{{ asset('storage/icons/djsessions.svg') }}" alt="Sesiones" class="h-8 w-8 {{ Route::is('djsessions.*') ? 'hidden' : 'block' }} group-hover:hidden">
                <img src="{{ asset('storage/icons/djsessions-selected.svg') }}" alt="Sesiones seleccionadas" class="h-11 w-11 {{ Route::is('djsessions.*') ? 'block' : 'hidden' }} group-hover:block">
            </a>
            <a href="/seguidos" class="p-2 relative group">
                <img src="{{ asset('storage/icons/seguidos.svg') }}" alt="Seguidos" class="h-8 w-8 {{ request()->is('seguidos') ? 'hidden' : 'block' }} group-hover:hidden">
                <img src="{{ asset('storage/icons/seguidos-selected.svg') }}" alt="Seguidos seleccionados" class="h-10 w-10 {{ request()->is('seguidos') ? 'block' : 'hidden' }} group-hover:block">
            </a>
        </div>
        
        <form action="{{ route('djsessions.search') }}" method="GET">
            <div class="flex items-center bg-white text-black px-4 py-2 rounded-full w-48 sm:w-64 md:w-80 lg:w-96">
                <button type="submit" class="focus:outline-none border-none bg-transparent p-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <input
                    type="text"
                    name="query"
                    placeholder="Buscar"
                    class="border-none focus:ring-0 outline-none bg-transparent w-full px-2"
                >
            </div>
        </form>
    </nav>
</div>
