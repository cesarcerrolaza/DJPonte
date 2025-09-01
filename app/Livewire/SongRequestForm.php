<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SongRequest;
use App\Events\NewSongRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\SortsSongRequests;
use App\Livewire\SongSearchable;
use Livewire\Attributes\On;

use Illuminate\Support\Facades\Log;

class SongRequestForm extends Component
{
    public $djsessionId;
    public $songName = '';
    public $artistName = '';
    public $songSuggestions = [];
    public $selectedSong = null;
    public $topSongs = [];
    public $topSongsCount = 5;
    public $user = null;
    public $userLastRequestAt = null;
    public $songRequestTimeout = null;

    use SongSearchable, SortsSongRequests;
    
    public function mount(Request $request, $djsessionId, $songRequestTimeout)
    {
        $this->djsessionId = $djsessionId;
        $this->user = $request->user();
        if ($this->user) {
            // Forzamos la obtención de los datos más recientes desde la BD
            $this->user = User::find($this->user->id);
            $this->userLastRequestAt = $this->user->last_request_at?->toIso8601String();
        }
        $this->songRequestTimeout = $songRequestTimeout;
        $this->topSongsCount = 5;
        $this->loadTopSongs();
    }

    
    public function loadTopSongs()
    {
        $songs = SongRequest::where('djsession_id', $this->djsessionId)
            ->with('song')
            ->get()
            ->map(fn($request) => [
                'id' => $request->id,
                'title' => $request->song_id ? $request->song->title : $request->custom_title,
                'artist' => $request->song_id ? $request->song->artist : $request->custom_artist,
                'score' => $request->score,
                'status' => $request->status,
            ])
            ->toArray();

        $this->topSongs = $this->sortRequests($songs, $this->topSongsCount);
    }
    
    public function voteSong($songRequestId)
    {
        $songRequest = SongRequest::find($songRequestId);
        if ($songRequest && $songRequest->status === 'pending') {
            $songRequest->score = $songRequest->score + 1;
            $songRequest->save();
            $this->user->last_request_at = now();
            $this->user->save();
            $this->userLastRequestAt = $this->user->last_request_at?->toIso8601String(); // Usar un formato estándar



            broadcast(new NewSongRequest($songRequest))->toOthers();
            
            $this->loadTopSongs();
            $this->dispatch("song-requested", timeout: $this->songRequestTimeout);
            $this->dispatch("voted-successfully.{$songRequestId}");
        }
    }
    
    public function submitRequest()
    {
        Log::info('Nueva request de canción enviada', [
            'djsessionId' => $this->djsessionId,
            'songName' => $this->songName,
            'artistName' => $this->artistName,
            'selectedSong' => $this->selectedSong
        ]);
        $this->validate([
            'songName' => 'required|string|max:255',
            'artistName' => 'required|string|max:255',
        ]);

        SongRequest::createSongRequest($this->djsessionId, [
            'title' => $this->songName,
            'artist' => $this->artistName,
            'songId' => $this->selectedSong
        ]);

        $this->user->last_request_at = now();
        $this->user->save();
        $this->userLastRequestAt = $this->user->last_request_at?->toIso8601String(); // Usar un formato estándar

        
        // Notificar éxito
        session()->flash('message', '¡Solicitud enviada!');
        
        // Limpiar el formulario
        $this->reset(['songName', 'artistName', 'selectedSong', 'songSuggestions']);
        
        $this->dispatch("song-requested", timeout: $this->songRequestTimeout);
        // Recargar las canciones top
        $this->loadTopSongs();
    }

    /**
     * Normaliza los datos del evento y completa la información desde la BD si hace falta.
     */
    protected function normalizeEventData(array $eventData): array
    {
        $id = $eventData['id'] ?? null;
        if (!$id) {
            Log::warning('Evento de canción recibido sin ID', ['payload' => $eventData]);
            return [];
        }

        $normalized = [
            'id'     => $id,
            'title'  => $eventData['title']  ?? null,
            'artist' => $eventData['artist'] ?? null,
            'score'  => $eventData['score']  ?? 0,
            'status' => $eventData['status'] ?? 'pending',
        ];

        // Si falta información relevante, traerla desde la BD
        if (empty($normalized['title']) || empty($normalized['artist']) || !isset($eventData['score']) || !isset($eventData['status'])) {
            $sr = SongRequest::with('song')->find($id);
            if ($sr) {
                $normalized['title']  = $normalized['title']  ?? ($sr->song?->title ?? $sr->custom_title);
                $normalized['artist'] = $normalized['artist'] ?? ($sr->song?->artist ?? $sr->custom_artist);
                $normalized['score']  = $eventData['score']  ?? $sr->score;
                $normalized['status'] = $eventData['status'] ?? $sr->status ?? 'pending';
            }
        }

        return $normalized;
    }


    #[On('echo:song-requests,NewSongRequest')]
    public function newRequest($eventData)
    {
        $entry = $this->normalizeEventData($eventData);
        if (empty($entry)) return;

        $this->topSongs = $this->topSongs ?? [];
        $index = collect($this->topSongs)->search(fn($r) => $r['id'] == $entry['id']);

        if ($index !== false) {
            $this->topSongs[$index] = array_merge($this->topSongs[$index], $entry);
        } else {
            $this->topSongs[] = $entry;
        }

        $this->topSongs = $this->sortRequests($this->topSongs, $this->topSongsCount);
    }

    #[On('echo:song-requests,SongRequestStatusUpdated')]
    public function updateRequestStatus($eventData)
    {
        $entry = $this->normalizeEventData($eventData);
        if (empty($entry)) return;

        $this->topSongs = $this->topSongs ?? [];
        $index = collect($this->topSongs)->search(fn($r) => $r['id'] == $entry['id']);

        if ($index !== false) {
            $this->topSongs[$index]['status'] = $entry['status'];
        } else {
            $this->topSongs[] = $entry;
        }

        $this->topSongs = $this->sortRequests($this->topSongs, $this->topSongsCount);
    }

    
    public function render()
    {
        return view('livewire.songs.song-request-form');
    }
}