<div class="flex w-full h-16 md:h-20">
    <!-- Sección morada (logo) -->
    <div class="bg-[#b481ff] w-56 md:w-64 lg:w-72 flex items-center justify-center px-4 py-3">
        <a href="/" class="flex items-center">
            <img src="{{ asset('storage/icons/djponte-logo.svg') }}" alt="DJ-PONTE" class="h-10 md:h-12 lg:h-14">
        </a>
    </div>
    
    <!-- Sección negra (navegación) -->
    <nav class="bg-black flex-1 flex items-center justify-between px-4 md:px-8 py-3 text-white">
        <!-- Navegación - Ahora centrada -->
        <div class="hidden md:flex items-center space-x-8 mx-auto">
            <a href="/home" class="p-2 relative group">
                <img src="{{ asset('storage/icons/home.svg') }}" alt="Inicio" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/home-selected.svg') }}" alt="Inicio seleccionado" class="h-8 w-8 hidden group-hover:block">
            </a>
            <a href="/djsessions" class="p-2 relative group">
                <img src="{{ asset('storage/icons/djsessions.svg') }}" alt="Sesiones" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/djsessions-selected.svg') }}" alt="Sesiones seleccionadas" class="h-8 w-8 hidden group-hover:block">
            </a>
            <a href="/seguidos" class="p-2 relative group">
                <img src="{{ asset('storage/icons/seguidos.svg') }}" alt="Seguidos" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/seguidos-selected.svg') }}" alt="Seguidos seleccionados" class="h-8 w-8 hidden group-hover:block">
            </a>
        </div>
        
        <!-- Versión móvil de los iconos (visible solo en móvil) -->
        <div class="flex md:hidden items-center space-x-6">
            <a href="/home" class="p-2 relative group">
                <img src="{{ asset('storage/icons/home.svg') }}" alt="Inicio" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/home-selected.svg') }}" alt="Inicio seleccionado" class="h-8 w-8 hidden group-hover:block absolute top-2 left-2">
            </a>
            <a href="/djsessions" class="p-2 relative group">
                <img src="{{ asset('storage/icons/djsessions.svg') }}" alt="Sesiones" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/djsessions-selected.svg') }}" alt="Sesiones seleccionadas" class="h-8 w-8 hidden group-hover:block absolute top-2 left-2">
            </a>
            <a href="/seguidos" class="p-2 relative group">
                <img src="{{ asset('storage/icons/seguidos.svg') }}" alt="Seguidos" class="h-8 w-8 group-hover:hidden">
                <img src="{{ asset('storage/icons/seguidos-selected.svg') }}" alt="Seguidos seleccionados" class="h-8 w-8 hidden group-hover:block absolute top-2 left-2">
            </a>
        </div>
        
        <!-- Barra de búsqueda - Ahora más ancha -->
        <div class="flex items-center bg-white text-black px-4 py-2 rounded-full w-48 sm:w-64 md:w-80 lg:w-96">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Buscar" class="border-none focus:ring-0 outline-none bg-transparent w-full px-2">
        </div>
    </nav>
</div>