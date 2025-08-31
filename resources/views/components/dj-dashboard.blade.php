<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Bienvenido de nuevo, {{ Auth::user()->name }}</h1>
        <p class="text-gray-400">Aquí tienes un resumen de tu actividad.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Sesión Activa o Crear Sesión --}}
            <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 border border-gray-700">
                @if($activeSession)
                    <h2 class="text-2xl font-bold text-white mb-2">Sesión en Vivo: {{ $activeSession->name }}</h2>
                    <p class="text-gray-400 mb-4">Tu público está interactuando ahora mismo.</p>
                    <div class="flex items-center space-x-6 text-white mb-6">
                        <div class="text-center">
                            {{-- Usuarios unidos --}}
                            <span class="block text-3xl font-semibold">{{ $activeSession->current_users }}</span>
                            <span class="text-sm text-gray-400">Usuarios Activos</span>
                        </div>
                        <div class="text-center">
                            {{-- Peticiones de canciones --}}
                            <span class="block text-3xl font-semibold">{{ $activeSession->songRequests()->count() }}</span>
                            <span class="text-sm text-gray-400">Peticiones</span>
                        </div>
                        <div class="text-center">
                            {{-- Propinas recibidas --}}
                            <span class="block text-3xl font-semibold">{{ number_format($activeSession->tips()->sum('amount') / 100, 2) }}€</span>
                            <span class="text-sm text-gray-400">Propinas Recibidas</span>
                        </div>
                    </div>
                    <a href="{{ route('djsessions.show', $activeSession->id) }}" class="block text-center bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">
                        Ir al Panel en Vivo
                    </a>
                @else
                    <h2 class="text-2xl font-bold text-white mb-2">¿Listo para empezar?</h2>
                    <p class="text-gray-400 mb-4">No tienes ninguna sesión activa en este momento.</p>
                    <a href="{{ route('djsessions.create') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">
                        + Crear Nueva Sesión
                    </a>
                @endif
            </div>

            {{-- Actividad Reciente --}}
            <div>
                <h3 class="text-xl font-semibold text-white mb-4">Actividad Reciente</h3>
                <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 text-gray-300 border border-gray-700">
                    
                    <ul class="space-y-4">
                        @forelse ($recentActivity as $activity)
                            
                            @if ($activity instanceof \App\Models\SongRequest)
                                <li class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3V3z" /></svg>
                                    </div>
                                    <div>
                                        <p>
                                            Nueva petición para
                                            <strong class="font-semibold text-indigo-400">"{{ $activity->song->title ?? $activity->custom_title }}"</strong>
                                        </p>
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endif

                            @if ($activity instanceof \App\Models\Tip)
                                <li class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-green-500/20 text-green-400 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11a1 1 0 11-2 0 1 1 0 012 0zM9.293 6.293a1 1 0 011.414 0l.001.001A1 1 0 0111 8a1 1 0 11-2 0 1 1 0 01.293-.707z" /></svg>
                                    </div>
                                    <div>
                                        <p>
                                            ¡Nueva propina de <strong class="font-semibold text-green-400">{{ number_format($activity->amount / 100, 2) }}€</strong>
                                            de <strong class="font-semibold text-white">{{ $activity->user->name ?? 'un usuario' }}</strong>!
                                        </p>
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endif

                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">Todavía no hay actividad reciente.</p>
                                <p class="text-gray-500">¡Crea una nueva sesión para empezar a interactuar!</p>
                            </div>
                        @endforelse
                    </ul>

                </div>
            </div>

        </div>

        <div class="space-y-8">
            {{-- Accesos Rápidos --}}
            <div>
                <h3 class="text-xl font-semibold text-white mb-4">Accesos Rápidos</h3>
                <div class="bg-gray-800/80 backdrop-blur-sm shadow-lg rounded-xl p-6 space-y-4 border border-gray-700">
                    <a href="{{ route('djsessions.index') }}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Ver todas mis sesiones</a>
                    <a href="{{ route('settings') }}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Gestionar mi perfil</a>
                    <a href="{{ route('social.management') }}" class="block text-indigo-400 hover:text-indigo-300 font-semibold">Conectar Redes Sociales</a>
                    <a href="{{ route('stripe.connect') }}" class="block text-yellow-500 hover:text-yellow-400 font-semibold">Configurar Pagos y Propinas con Stripe</a>
                </div>
            </div>
        </div>
    </div>
</div>

