<div>
    <h3 class="text-xl font-bold mb-4 bg-gradient-to-r text-purple-600 bg-clip-text border-b-2 border-purple-300 pb-2">Peticiones de Canciones</h3>
    <ul>
        @foreach($requests as $index => $request)
            <li class="py-2 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 flex items-center">
                @if($index === 0)
                    <!-- Trofeo de Oro -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#FFD700" d="M12 2l1.5 4.5h5l-4 3.5 1.5 4.5-4-3.5-4 3.5 1.5-4.5-4-3.5h5z" />
                        <path fill="#FFD700" d="M6.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <path fill="#FFD700" d="M19.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <rect fill="#FFD700" x="9" y="14" width="6" height="7" rx="1" />
                    </svg>
                @elseif($index === 1)
                    <!-- Trofeo de Plata -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#C0C0C0" d="M12 2l1.5 4.5h5l-4 3.5 1.5 4.5-4-3.5-4 3.5 1.5-4.5-4-3.5h5z" />
                        <path fill="#C0C0C0" d="M6.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <path fill="#C0C0C0" d="M19.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <rect fill="#C0C0C0" x="9" y="14" width="6" height="7" rx="1" />
                    </svg>
                @elseif($index === 2)
                    <!-- Trofeo de Bronce -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#CD7F32" d="M12 2l1.5 4.5h5l-4 3.5 1.5 4.5-4-3.5-4 3.5 1.5-4.5-4-3.5h5z" />
                        <path fill="#CD7F32" d="M6.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <path fill="#CD7F32" d="M19.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
                        <rect fill="#CD7F32" x="9" y="14" width="6" height="7" rx="1" />
                    </svg>
                @else
                    <span class="ml-1 w-5 mr-2 inline-block text-gray-400">🎵</span>
                @endif
                <div class="flex-1 flex justify-between items-center">
                    <span><strong class="text-purple-700">{{ $request['title'] }}</strong> - <span class="text-gray-600">{{ $request['artist'] }}</span></span>
                    <span class="ml-2 px-2 py-1 text-s font-semibold rounded bg-purple-100 text-purple-800">Score: {{ $request['score'] }}</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>