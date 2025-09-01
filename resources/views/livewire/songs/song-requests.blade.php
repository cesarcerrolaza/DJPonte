<div>
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
                    
                    <div class="flex items-center space-x-2">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Disponible'],
                                'attended' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-600', 'label' => 'Atendida'],
                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Rechazada'],
                            ];

                            $status = $request['status'] ?? 'pending';
                        @endphp

                        <span class="ml-2 px-2 py-1 text-s font-semibold rounded {{ $statusColors[$status]['bg'] }} {{ $statusColors[$status]['text'] }}">
                            {{ $statusColors[$status]['label'] }}
                        </span>

                        <span class="ml-2 px-2 py-1 text-s font-semibold rounded bg-purple-100 text-purple-800">Score: {{ $request['score'] }}</span>
                    
                        <x-dropdown width="12">
                            <x-slot name="trigger">
                                <button class="p-2 rounded-full hover:bg-gray-200 transition">
                                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 10a2 2 0 114.001.001A2 2 0 016 10zm4-4a2 2 0 110-4 2 2 0 010 4zm0 12a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if($request['status'] !== 'rejected')
                                <x-dropdown-button 
                                    wire:click="updateSongRequestStatus({{ $request['id'] }}, 'rejected')"
                                    class="text-red-600 dark:text-red-400"
                                >
                                    Rechazar
                                </x-dropdown-button>
                                @endif

                                @if($request['status'] !== 'attended')
                                <x-dropdown-button wire:click="updateSongRequestStatus({{ $request['id'] }}, 'attended')">
                                    Atender
                                </x-dropdown-button>
                                @endif

                                @if($request['status'] !== 'pending')
                                <x-dropdown-button 
                                    wire:click="updateSongRequestStatus({{ $request['id'] }}, 'pending')"
                                    class="!text-blue-400 dark:!text-blue-300"
                                >
                                    Disponible
                                </x-dropdown-button>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>
                
                </div>
            </li>
        @endforeach
    </ul>
</div>