<div>
    <h3 class="text-xl font-bold mb-4 bg-gradient-to-r text-purple-600 bg-clip-text border-b-2 border-purple-300 pb-2">Peticiones de Canciones</h3>
    <ul>
        @foreach($requests as $index => $request)
            <li class="py-2 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 flex items-center">
                @if($index <= 2)
                    <x-trophy-icon :index="$index" />
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