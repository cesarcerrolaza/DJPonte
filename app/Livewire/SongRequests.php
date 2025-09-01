<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\SongRequest;
use App\Traits\SortsSongRequests;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;


class SongRequests extends Component
{
    public $requests = [];

    use SortsSongRequests;

    public function mount($djsessionId)
    {
        //Ordenar por score
        $this->loadRequests($djsessionId);
    }

    public function loadRequests($djsessionId)
    {
        Log::info('Peticiones cargadas');

        $this->requests = SongRequest::where('djsession_id', $djsessionId)
            ->with('song')
            ->orderByRaw("
                CASE status
                    WHEN 'new' THEN 1
                    WHEN 'attended' THEN 2
                    WHEN 'rejected' THEN 3
                END
            ")
            ->orderBy('score', 'desc')
            ->take(10)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => optional($request->song)->title ?? $request->custom_title,
                    'artist' => optional($request->song)->artist ?? $request->custom_artist,
                    'score' => $request->score,
                    'status' => $request->status,
                ];
            })
            ->toArray();
    }


    #[On('echo:song-requests,NewSongRequest')]
    public function addRequest($eventData)
    {
        $index = collect($this->requests)->search(fn ($r) => $r['id'] === $eventData['id']);

        if ($index !== false) {
            $this->requests[$index]['score'] = $eventData['score'];
            $this->requests[$index]['status'] = $eventData['status'];
        } else {
            $this->requests[] = $eventData;
        }

        $this->requests = $this->sortRequests($this->requests, 10);
    }

    /**
     * Actualiza el estado de una petición y la retransmite.
     *
     * @param int $id
     * @param string $status  // 'attended', 'rejected' o 'pending'
     */
    public function updateSongRequestStatus($id, $status)
    {
        $request = SongRequest::find($id);

        if (!$request) {
            Log::warning("Intento de actualizar petición inexistente: {$id}");
            return;
        }

        // Solo permitir cambios a estados válidos
        if (!in_array($status, ['attended', 'rejected', 'pending'])) {
            Log::warning("Estado no válido para la petición {$id}: {$status}");
            return;
        }

        $request->changeStatus($status);

        // Actualizar la lista local
        $index = collect($this->requests)->search(fn($r) => $r['id'] === $id);
        if ($index !== false) {
            $this->requests[$index]['status'] = $status;
        }

        $this->requests = $this->sortRequests($this->requests, 10);
    }

    public function render()
    {
        return view('livewire.songs.song-requests');
    }
}

