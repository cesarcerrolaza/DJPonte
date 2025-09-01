<div class="mt-6 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <h2 class="text-xl font-semibold text-indigo-600 mb-4">Resumen de la Sesión</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-purple-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    Canciones
                </h3>
                <span class="bg-purple-200 text-purple-800 font-bold text-sm px-3 py-1 rounded-full">
                    {{ $songRequestsCount }}
                </span>
            </div>
            
            @if($topSongRequests && count($topSongRequests) > 0)
                <div class="mt-3 space-y-1">
                    @foreach($topSongRequests as $index => $song)
                        <div class="bg-white border-l-4 {{ $index < 3 ? ['border-yellow-400', 'border-gray-300', 'border-amber-600'][$index] : 'border-transparent' }} rounded shadow-sm p-2 flex items-center hover:bg-gray-50 transition-colors duration-200">
                            @if ($song['status'] === 'attended')
                                <span class="ml-1 w-5 mr-2 inline-block text-green-400">✔️</span>
                            @elseif ($song['status'] === 'rejected')
                                <span class="ml-1 w-5 mr-2 inline-block text-red-400">❌</span>
                            @endif
                            @if ($index < 3)
                                @php
                                    $textColors = [
                                        0 => 'text-yellow-500',
                                        1 => 'text-gray-400',
                                        2 => 'text-amber-600',
                                    ];
                                @endphp
                                <div class="flex-shrink-0 w-6 text-center">
                                    <span class="{{ $textColors[$index] }} font-extrabold text-sm font-mono">#{{ $index + 1 }}</span>
                                </div>
                            @else
                                <div class="flex-shrink-0 w-6 text-center">
                                    <span class="text-gray-400 text-xs font-medium">{{ $index + 1 }}</span>
                                </div>
                            @endif
                            
                            <div class="flex-grow ml-2 truncate">
                                <p class="text-sm text-purple-900 font-medium font-sans truncate">{{ $song['title'] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $song['artist'] }}</p>
                            </div>
                            
                            <div class="flex-shrink-0">
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">
                                    {{ $song['score'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-600 font-medium">
                    No hay canciones solicitadas todavía.
                </p>
            @endif
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-yellow-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Propinas
                </h3>
                <span class="bg-yellow-200 text-yellow-800 font-bold text-sm px-3 py-1 rounded-full">
                    {{ $tipsTotal/100 ?? 0 }} €
                </span>
            </div>
            <p class="mt-2 text-sm text-yellow-600">
                @if($tipsTotal > 0)
                    <livewire:top-donors :djsessionId="$djsession->id" :viewType="'summary'" :key="'summary'" />
                @else
                    No has recibido propinas todavía.
                @endif
            </p>
        </div>
        
        <div class="bg-pink-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-pink-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Sorteos
                </h3>
                <span class="bg-pink-200 text-pink-800 font-bold text-sm px-3 py-1 rounded-full">
                    {{ $rafflesCount ?? 0 }}
                </span>
            </div>
                <p class="mt-2 text-sm text-pink-600">
                    <livewire:raffle-info :djsessionId="$djsession->id" :viewType="'summary'" :key="$raffleInfoKey" />
                </p>
            </div>
        </div>
    
</div>