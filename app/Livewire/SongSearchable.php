<?php

namespace App\Livewire;

use App\Models\Song;

trait SongSearchable
{
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
}