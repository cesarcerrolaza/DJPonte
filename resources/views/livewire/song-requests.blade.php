<div>
    <h3 class="text-xl font-bold">Peticiones de Canciones</h3>
    <ul>
        @foreach($requests as $request)
            <li class="py-2 border-b">
                🎵 <strong>{{ $request['title'] }}</strong> - {{ $request['artist'] }} (Score: {{ $request['score'] }})
            </li>
        @endforeach
    </ul>
</div>
