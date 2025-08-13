<footer class="bg-gray-900">
    <div class="container mx-auto py-8 px-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="w-full md:w-1/3 text-center md:text-left mb-4 md:mb-0">
                <a href="{{ route('home') }}" class="flex items-center justify-center md:justify-start gap-2 text-white text-xl font-bold">
                    <img src="{{ asset('storage/icons/djponte-logo.svg') }}" alt="DJPonte Logo" class="h-40 w-40">
                </a>
            </div>
            <div class="w-full md:w-1/3 text-center mb-4 md:mb-0">
                <p class="text-gray-400">&copy; {{ date('Y') }} DJPonte. Todos los derechos reservados.</p>
            </div>
            <div class="w-full md:w-1/3 text-center md:text-right">
                <a href="{{ route('policy') }}" class="text-gray-400 hover:text-white mx-2">Privacidad</a>
            </div>
        </div>
    </div>
</footer>