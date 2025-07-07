<div class="mt-3">
    @if($raffle != null)
        <div class="relative flex items-start space-x-3 p-3 bg-gradient-to-r from-pink-25 to-pink-50 rounded-lg border border-pink-100">
            <!-- Imagen del premio -->
            <div class="flex-shrink-0">
                <div class="w-14 h-14 rounded-xl overflow-hidden shadow-md border-2 border-white">
                    <img src="{{ $raffle->prize_image_url }}" 
                         alt="{{ $raffle->prize_name }}" 
                         class="object-cover w-full h-full hover:scale-105 transition-transform duration-200">
                </div>
            </div>

            <!-- Información principal -->
            <div class="flex-1 min-w-0">
                <!-- Cabecera con título y estado -->
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1 min-w-0 pr-2">
                        <h4 class="text-lg font-bold text-gray-800 line-clamp-1 leading-tight">
                            {{ $raffle->prize_name }}
                        </h4>
                    </div>
                    
                    <div class="flex-shrink-0">
                        @if($raffle->isDraft())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                                Borrador
                            </span>
                        @elseif($raffle->isOpen())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></div>
                                Activo
                            </span>
                        @elseif($raffle->isClosed())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 border border-orange-200">
                                <div class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-1.5"></div>
                                Cerrado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-400 text-gray-800 border border-gray-900">
                                <div class="w-1.5 h-1.5 bg-gray-700 rounded-full mr-1.5"></div>
                                Finalizado
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Ganador o Último participante -->
                @if($raffle->winner)
                    <div class="flex items-center space-x-2 p-2 bg-pink-100 rounded-lg border border-pink-200">
                        <div class="flex-shrink-0 w-6 h-6 bg-pink-700 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="..."/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-pink-600 font-medium">Ganador</p>
                            <p class="text-sm font-bold text-pink-700 truncate">{{ $raffle->winner->name }}</p>
                        </div>
                    </div>
                @elseif($this->lastParticipant)
                    <div class="flex items-center space-x-2 p-2 bg-pink-50 rounded-lg border border-pink-100">
                        <div class="flex-shrink-0 w-6 h-6 bg-pink-100 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="..."/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-pink-600 font-medium">Último</p>
                            <p class="text-sm font-bold text-pink-700 truncate">{{ $this->lastParticipant }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-2 p-2 bg-pink-50 rounded-lg border border-pink-100">
                        <div class="flex-shrink-0 w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 font-medium">Estado</p>
                            <p class="text-sm font-bold text-gray-600">Sin actividad</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Participantes (posicionado abajo a la izquierda) -->
            <div class="absolute bottom-2 left-3 text-sm font-bold text-gray-800">
                👥 {{ $raffle->participants_count }}
            </div>
        </div>
    @else
        <!-- Estado sin sorteos -->
        <div class="flex items-center justify-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-600 font-semibold mb-1">
                    Sin sorteos activos
                </p>
                <p class="text-xs text-gray-500">
                    Los sorteos aparecerán aquí cuando estén disponibles
                </p>
            </div>
        </div>
    @endif
</div>
