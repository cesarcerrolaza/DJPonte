<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class DjsessionSummary extends Component
{
    public $djsession;
    public $songRequestsCount;
    public $topSongsCount = 3;
    public $topSongRequests;
    public $tipsTotal;
    public $rafflesCount;
    public $showRaffle = true;
    public $raffleInfoKey;


    public function render()
    {
        return view('livewire.djsession-summary');
    }

    public function getListeners()
    {
        return [
            "echo-private:djsession.{$this->djsession->id},NewTip" => 'handleNewTip',
            "echo:djsession.{$this->djsession->id},CurrentRaffleDeleted" => 'refreshRaffle',
        ];
    }

    public function mount() {
        //Sumatorio de todos los count de todos los requests
        $this->songRequestsCount = $this->djsession->songRequests()->sum('score');
        $this->topSongRequests = $this->djsession->songRequests()->with('song')
            ->orderBy('score', 'desc')
            ->take($this->topSongsCount)
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

        $this->tipsTotal = $this->djsession->tips()->where('status', 'paid')->sum('amount');

        $this->rafflesCount = $this->djsession->raffles()->count();
        $this->refreshRaffle();
    }

    #[On('echo:song-requests,NewSongRequest')]
    public function addRequest($eventData)
    {

        // Buscar si la solicitud ya está en la lista por el ID y obtener la clave (índice)
        $index = collect($this->topSongRequests)->search(fn ($r) => $r['id'] === $eventData['id']);

        $this->songRequestsCount = $this->songRequestsCount + 1;

        if ($index !== false) {
            // Si ya existe, actualizar el score
            $this->topSongRequests[$index]['score'] = $eventData['score'];
            // Reordenar la lista después de añadir o actualizar la petición
            usort($this->topSongRequests, fn ($a, $b) => $b['score'] <=> $a['score']);
        } else{
            // Si no existe, comprobar los score
            if (count($this->topSongRequests) < $this->topSongsCount) {
                // Si hay espacio, añadir la nueva petición
                $this->topSongRequests[] = $eventData;
            } else {
                // Si no hay espacio, comprobar si el score es mayor que el menor de los 3 mejores
                if ($this->topSongRequests[2]['score'] < $eventData['score']) {
                    // Reemplazar la de menor score
                    $this->topSongRequests[2] = $eventData;
                    usort($this->topSongRequests, fn ($a, $b) => $b['score'] <=> $a['score']);
                }
            }
        }
    }


    public function handleNewTip($payload)
    {
        if ($payload['status'] === 'paid') {
            $this->tipsTotal = $this->tipsTotal + $payload['donor_amount'];
        }
    }

    public function refreshRaffle()
    {
        $this->raffleInfoKey = 'raffle-summary-' . uniqid();
        Log::info('SUMMARY: Refreshing raffle with key: ' . $this->raffleInfoKey);
    }
}
