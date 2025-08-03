{{-- En resources/views/social-management.blade.php --}}

@extends('layouts/app')
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium mb-4">Selecciona la publicación para las peticiones</h3>

                    @if (empty($posts) && Auth::user()->socialAccounts()->where('platform', 'instagram')->exists())
                        <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded">
                            <p class="font-medium">No se pudieron cargar tus publicaciones.</p>
                            <p class="text-sm">Es posible que la conexión con Instagram haya caducado. Por favor, vuelve a conectar tu cuenta para refrescar el acceso.</p>
                            <a href="{{ route('instagram.reconnect') }}" class="mt-2 inline-block bg-blue-500 text-white py-1 px-3 rounded text-sm font-bold">
                                Reconectar Cuenta
                            </a>
                        </div>
                    @endif


                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse ($posts as $post)
                            <div class="border rounded-lg p-2 flex flex-col justify-between">
                                <div>
                                    <img src="{{ $post['media_url'] ?? $post['thumbnail_url'] }}" alt="Post de Instagram" class="w-full h-48 object-cover rounded">
                                    <p class="text-sm mt-2 text-gray-600">{{ Str::limit($post['caption'] ?? 'Sin descripción', 100) }}</p>
                                </div>

                                <div class="mt-2">
                                    @if ($activePost && $activePost->media_id == $post['id'])
                                        <p class="text-sm font-bold text-green-600 text-center py-2">✔️ Publicación Activa</p>
                                    @else
                                        <form action="{{ route('setMonitoredPost') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="media_id" value="{{ $post['id'] }}">
                                            <input type="hidden" name="platform" value="instagram"> {{-- O la plataforma que corresponda --}}
                                            <input type="hidden" name="caption" value="{{ $post['caption'] ?? '' }}">
                                            <input type="hidden" name="media_url" value="{{ $post['media_url'] ?? $post['thumbnail_url'] }}">
                                            <input type="hidden" name="permalink" value="{{ $post['permalink'] ?? '' }}">

                                            <button type="submit" class="w-full bg-blue-500 text-white py-1 rounded hover:bg-blue-600">
                                                Seleccionar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p>No se encontraron publicaciones recientes o no se pudo conectar con la API.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection