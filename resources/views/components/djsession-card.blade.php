<a href="{{ route('djsessions.show', $djsession) }}" class="block hover:shadow-lg transition-shadow duration-200">
    <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md w-full max-w-5xl mx-auto">
        <!-- Imagen del local -->
        <div class="w-28 h-28 rounded overflow-hidden mr-4">
            <img src="{{ asset($djsession->image) }}" alt="{{ $djsession->name }}" class="object-cover w-full h-full">
        </div>

        <!-- Info principal -->
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold">{{ $djsession->name }}</h2>
                <span class="text-gray-400 font-bold text-lg">#{{ $djsession->code }}</span>
            </div>

            <p class="text-purple-600 font-semibold text-sm flex items-center mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243m0 0L5.636 18.364a9 9 0 1112.728 0l-1.414-1.414z" />
                </svg>
                {{ $location }}
            </p>

            <div class="flex items-center mt-2">
                <img src="{{ asset($djAvatar) }}" alt="{{ $djName }}" class="w-6 h-6 rounded-full mr-2">
                <span class="text-sm font-semibold">{{ $djName }}</span>
            </div>
        </div>

        <!-- Participantes -->
        <div class="flex flex-col items-end justify-between h-full ml-4">
            <div class="text-sm text-black mt-4 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 7H7v6h6v4l5-5-5-5v4z"/>
                </svg>
                {{ $djsession->current_users }} Participantes
            </div>
        </div>
    </div>
</a>
