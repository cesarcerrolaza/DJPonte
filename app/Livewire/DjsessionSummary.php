<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\SortsSongRequests;

class DjsessionSummary extends Component
{
    use SortsSongRequests;

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
            "echo:song-requests,NewSongRequest" => 'addRequest',
            "echo:song-requests,SongRequestStatusUpdated" => 'updateRequestStatus',
        ];
    }

    public function mount() 
    {
        if (!$this->djsession) {
            $this->djsession = Auth::user()->djsessionActive;
        }

        $this->loadSummaryData();
    }

    protected function loadSummaryData()
    {
        $this->songRequestsCount = $this->djsession->songRequests()->sum('score');

        $requests = $this->djsession->songRequests()
            ->with('song')
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

        $this->topSongRequests = $this->sortRequests($requests, $this->topSongsCount);

        $this->tipsTotal = $this->djsession->tips()->where('status', 'paid')->sum('amount');

        $this->rafflesCount = $this->djsession->raffles()->count();
        $this->refreshRaffle();
    }


    public function addRequest($eventData)
    {
        $index = collect($this->topSongRequests)->search(fn($r) => $r['id'] === $eventData['id']);

        $this->songRequestsCount = $this->songRequestsCount + 1;

        if ($index !== false) {
            $this->topSongRequests[$index]['score'] = $eventData['score'];
            $this->topSongRequests[$index]['status'] = $eventData['status'];
        } else {
            $this->topSongRequests[] = $eventData;
        }

        $this->topSongRequests = $this->sortRequests($this->topSongRequests, $this->topSongsCount);
    }

    public function updateRequestStatus($eventData)
    {
        $index = collect($this->topSongRequests)->search(fn($r) => $r['id'] === $eventData['id']);

        if ($index !== false) {
            $this->topSongRequests[$index]['status'] = $eventData['status'];
        }

        $this->topSongRequests = $this->sortRequests($this->topSongRequests, $this->topSongsCount);
    }

    public function handleNewTip($payload)
    {
        if ($payload['status'] === 'paid') {
            $this->tipsTotal += $payload['donor_amount'];
        }
    }

    public function refreshRaffle()
    {
        $this->raffleInfoKey = 'raffle-summary-' . uniqid();
        Log::info('SUMMARY: Refreshing raffle with key: ' . $this->raffleInfoKey);
    }
}
