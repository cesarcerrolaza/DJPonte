<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\SongRequest;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;


class SongRequests extends Component
{
    public $requests = [];

    public function mount($djsessionId)
    {
        //Ordenar por score
        $this->loadRequests($djsessionId);
    }

    public function loadRequests($djsessionId)
    {
        Log::info('Comentarios cargados');

        $this->requests = SongRequest::where('djsession_id', $djsessionId)
            ->with('song')
            ->orderBy('score', 'desc')
            ->take(10)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => optional($request->song)->title ?? $request->custom_title,
                    'artist' => optional($request->song)->artist ?? $request->custom_artist,
                    'score' => $request->score
                ];
            })
            ->toArray();
    }

    #[On('echo:song-requests,NewSongRequest')]
    public function addRequest($eventData)
    {
        Log::info('Nuevo comentario recibido por broadcast:', $eventData);

        // Buscar si la solicitud ya está en la lista por el ID y obtener la clave (índice)
        $index = collect($this->requests)->search(fn ($r) => $r['id'] === $eventData['id']);

        if ($index !== false) {
            $this->requests[$index]['score'] = $eventData['score'];
        } else {
            $this->requests[] = $eventData;
        }

        // Ordenar la lista después de añadir o actualizar la petición
        usort($this->requests, fn ($a, $b) => $b['score'] <=> $a['score']);

        // Mantener solo las 10 mejores peticiones
        $this->requests = array_slice($this->requests, 0, 10);
    }



    public function render()
    {
        return view('livewire.songs.song-requests');
    }
}

