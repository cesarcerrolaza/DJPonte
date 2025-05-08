<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SongRequest;
use App\Models\Song;
use App\Events\NewSongRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SongRequestForm extends Component
{
    public $djsessionId;
    public $songName = '';
    public $artistName = '';
    public $songSuggestions = [];
    public $selectedSong = null;
    public $topSongs = [];
    public $userId = null;
    public $userLastRequestAt = null;
    public $songRequestTimeout = null;
    
    public function mount(Request $request, $djsessionId, $songRequestTimeout)
    {
        $this->djsessionId = $djsessionId;
        $this->userId = $request->user()->id;
        $this->userLastRequestAt = $request->user()->last_request_at;
        $this->songRequestTimeout = $songRequestTimeout;
        $this->loadTopSongs();
    }
    
    public function updatedSongName()
    {
        $this->searchSongs();
    }
    
    public function searchSongs()
    {
        if (strlen($this->songName) < 2) {
            $this->songSuggestions = [];
            return;
        }
        
        $this->songSuggestions = Song::where('title', 'LIKE', '%' . $this->songName . '%')
            ->take(5)
            ->get()
            ->map(function($song) {
                return [
                    'id' => $song->id,
                    'title' => $song->title,
                    'artist' => $song->artist
                ];
            })
            ->toArray();
    }
    
    public function selectSong($songId)
    {
        $song = Song::find($songId);
        if ($song) {
            $this->songName = $song->title;
            $this->artistName = $song->artist;
            $this->selectedSong = $song->id;
        }
        $this->songSuggestions = [];
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
            $this->userLastRequestAt = now();
            User::where('id', $this->userId)->update(['last_request_at' => $this->userLastRequestAt]);


            broadcast(new NewSongRequest($songRequest));
            
            $this->loadTopSongs();
            $this->dispatch("song-requested");
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

        $this->userLastRequestAt = now();
        User::where('id', $this->userId)->update(['last_request_at' => $this->userLastRequestAt]);
        
        // Notificar éxito
        session()->flash('message', '¡Solicitud enviada!');
        
        // Limpiar el formulario
        $this->reset(['songName', 'artistName', 'selectedSong', 'songSuggestions']);
        
        $this->dispatch("song-requested");
        // Recargar las canciones top
        $this->loadTopSongs();
    }
    
    public function render()
    {
        return view('livewire.song-request-form');
    }
}