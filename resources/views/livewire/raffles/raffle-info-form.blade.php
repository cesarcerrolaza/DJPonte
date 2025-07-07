<div class="mt-3">
    @if($raffle != null)
        <!-- Header con título del premio -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ $raffle->prize_name }}
            </h2>
            
            <!-- Badge de estado -->
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($raffle->isOpen())
                    bg-green-100 text-green-800 border border-green-200
                @elseif($raffle->isClosed())
                    bg-yellow-100 text-yellow-800 border border-yellow-200
                @else
                    bg-gray-100 text-gray-800 border border-gray-200
                @endif
            ">
                @if($raffle->isOpen())
                    🟢 Abierto
                @elseif($raffle->isClosed())
                    🟡 Cerrado
                @else
                    ⚫ Finalizado
                @endif
            </span>
        </div>

        <!-- Contenido principal -->
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-4 border border-pink-200">
            <div class="flex items-start space-x-4">
                <!-- Imagen del premio -->
                <div class="flex-shrink-0">
                    <div class="w-28 h-28 rounded-lg overflow-hidden shadow-md border-2 border-white">
                        <img src="{{ $raffle->prize_image_url }}" alt="{{ $raffle->prize_name }}" class="object-cover w-full h-full">
                    </div>
                </div>

                <!-- Información del sorteo -->
                <div class="flex-1 space-y-3">
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold text-gray-800">📝 Descripción:</span><br>
                            <span class="text-gray-600">{{ $raffle->description }}</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Participantes -->
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="flex items-center">
                                <span class="text-2xl mr-2">👥</span>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Participantes</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $raffle->participants_count }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ganador -->
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="flex items-center">
                                @if($raffle->winner)
                                    <span class="text-2xl mr-2">🏆</span>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Ganador</p>
                                        <p class="text-sm font-bold text-yellow-600">{{ $raffle->winner->name }}</p>
                                    </div>
                                @else
                                    <span class="text-2xl mr-2">⏳</span>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Ganador</p>
                                        <p class="text-sm font-medium text-gray-500">Sin determinar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Último participante -->
                    @if($this->lastParticipant)
                        <div class="bg-white rounded-lg p-3 shadow-sm border-l-4 border-pink-400">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">🆕</span>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Último participante</p>
                                    <p class="text-sm font-semibold text-pink-600">{{ $this->lastParticipant }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <x-roulette-user/>
    @else
        <div class="text-center py-8">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.87 0-5.47 1.508-6.927 4.008a9.021 9.021 0 01-1.071-1.716c-.553-.978-.86-2.1-.86-3.292 0-.849.12-1.671.342-2.454C5.09 8.904 8.278 7 12 7s6.91 1.904 8.516 4.546c.222.783.342 1.605.342 2.454 0 1.192-.307 2.314-.86 3.292a9.021 9.021 0 01-1.071 1.716A7.962 7.962 0 0112 15z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">
                No hay sorteos en la djsession todavía.
            </p>
            <p class="text-sm text-gray-400 mt-1">
                ¡Mantente atento para participar en futuros sorteos!
            </p>
        </div>
    @endif
</div>