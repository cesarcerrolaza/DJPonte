<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SongRequest;
use App\Events\NewSongRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Livewire\SongSearchable;
use Illuminate\Support\Facades\Log;

class SongRequestForm extends Component
{
    public $djsessionId;
    public $songName = '';
    public $artistName = '';
    public $songSuggestions = [];
    public $selectedSong = null;
    public $topSongs = [];
    public $user = null;
    public $userLastRequestAt = null;
    public $songRequestTimeout = null;

    use SongSearchable;
    
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
        $this->loadTopSongs();
    }

    
    public function loadTopSongs()
    {
        $this->topSongs = SongRequest::where('djsession_id', $this->djsessionId)
            ->orderBy('score', 'desc')
            ->take(5)
            ->get()
            ->map(function($request) {
                $title = $request->song_id ? $request->song->title : $request->custom_title;
                $artist = $request->song_id ? $request->song->artist : $request->custom_artist;
                
                return [
                    'id' => $request->id,
                    'title' => $title,
                    'artist' => $artist,
                    'score' => $request->score
                ];
            });
    }
    
    public function voteSong($songRequestId)
    {
        $songRequest = SongRequest::find($songRequestId);
        if ($songRequest) {
            $songRequest->score = $songRequest->score + 1;
            $songRequest->save();
            $this->user->last_request_at = now();
            $this->user->save();
            $this->userLastRequestAt = $this->user->last_request_at?->toIso8601String(); // Usar un formato estándar



            broadcast(new NewSongRequest($songRequest));
            
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
    
    public function render()
    {
        return view('livewire.songs.song-request-form');
    }
}