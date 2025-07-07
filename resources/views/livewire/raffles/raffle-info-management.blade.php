<div class="mt-3">
    @if($raffle != null)
        <!-- Header con título del premio y estado -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Premio:
                {{ $raffle->prize_name }}
            </h2>
            
        </div>

        <!-- Panel principal de gestión -->
        <div class="bg-white rounded-lg border-2 
            @if($raffle->isOpen())
                border-green-200 shadow-green-100
            @elseif($raffle->isClosed())
                border-yellow-200 shadow-yellow-100
            @else
                border-gray-200 shadow-gray-100
            @endif
            shadow-lg overflow-hidden">
            
            <!-- Header del panel -->
            <div class="
                @if($raffle->isOpen())
                    bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200
                @elseif($raffle->isClosed())
                    bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-yellow-200
                @else
                    bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200
                @endif
                px-6 py-4">
                
                <div class="flex items-start space-x-4">
                    <!-- Imagen del premio -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 rounded-lg overflow-hidden shadow-md border-2 border-white">
                            <img src="{{ $raffle->prize_image_url }}" alt="{{ $raffle->prize_name }}" class="object-cover w-full h-full">
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Descripción del sorteo</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $raffle->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del sorteo -->
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <!-- Participantes -->
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-3xl font-bold text-blue-600">{{ $raffle->participants_count }}</div>
                        <p class="text-lg text-blue-700 font-medium mt-1">👥 Participantes</p>
                    </div>

                    <!-- Ganador -->
                    <div class="text-center p-4 
                        @if($raffle->winner)
                            bg-yellow-50 border-yellow-200
                        @else
                            bg-gray-50 border-gray-200
                        @endif
                        rounded-lg border">
                        @if($raffle->winner)
                            <div class="text-lg font-bold text-yellow-600 mb-1">🏆</div>
                            <p class="text-lg font-semibold text-yellow-700">{{ $raffle->winner->name }}</p>
                            <p class="text-sm text-yellow-600 mt-1">Ganador</p>
                        @else
                            <div class="text-2xl font-bold text-gray-400 mb-1">⏳</div>
                            <p class="text-lg text-gray-500 font-medium">Sin determinar</p>
                            <p class="text-sm text-gray-400 mt-1">Ganador</p>
                        @endif
                    </div>

                    <!-- Último participante -->
                    <div class="text-center p-4 bg-pink-50 rounded-lg border border-pink-200">
                        @if($this->lastParticipant)
                            <div class="text-lg font-bold text-pink-600 mb-1">🆕</div>
                            <p class="text-lg font-semibold text-pink-700">{{ $this->lastParticipant }}</p>
                            <p class="text-sm text-pink-600 mt-1">Último participante</p>
                        @else
                            <div class="text-2xl font-bold text-gray-400 mb-1">➖</div>
                            <p class="text-lg text-gray-500 font-medium">Sin participantes</p>
                            <p class="text-sm text-gray-400 mt-1">Último participante</p>
                        @endif
                    </div>
                </div>

                <!-- Indicador de actividad para sorteos abiertos -->
                @if($raffle->isOpen())
                    <div class="bg-green-100 border border-green-300 rounded-lg p-3 mb-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                            <p class="text-sm text-green-800 font-medium">
                                El sorteo está activo y recibiendo participaciones
                            </p>
                        </div>
                    </div>
                @elseif($raffle->isClosed())
                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-3 mb-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <p class="text-sm text-yellow-800 font-medium">
                                Participaciones cerradas - Listo para sortear
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
            <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Sin sorteos activos</h3>
            <p class="text-gray-500 font-medium mb-1">
                No hay sorteos en la djsession todavía.
            </p>
            <p class="text-sm text-gray-400">
                Crea un nuevo sorteo para comenzar a recibir participaciones.
            </p>
        </div>
    @endif
</div>