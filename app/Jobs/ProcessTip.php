<?php

namespace App\Jobs;

use App\Models\Tip;
use App\Models\Donor;
use App\Models\SongRequest;
use App\Events\NewTip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stripePaymentIntentId;
    protected $status;

    public function __construct(string $stripePaymentIntentId, string $status)
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;
        $this->status = $status;
    }

    public function handle()
    {
        $tip = Tip::where('stripe_session_id', $this->stripePaymentIntentId)->first();

        if (!$tip || $tip->status === 'paid') return;

        $tip->status = $this->status;
        $tip->save();

        $donor = Donor::where('user_id', $tip->user_id)->first();
        if ($donor) {
            $donor->increment('amount', $tip->amount);
        }
        else {
            $donor = Donor::create([
                'user_id' => $tip->user_id,
                'djsession_id' => $tip->djsession_id,
                'amount' => $tip->amount,
                'currency' => $tip->currency,
            ]);
        }

        SongRequest::createSongRequest($tip->djsession_id, [
            'title' => $tip->custom_title,
            'artist' => $tip->custom_artist,
            'songId' => $tip->song_id,
        ]);

        broadcast(new NewTip($tip->id, $this->status, $tip->djsession_id, $tip->user_id, $tip->amount, $donor->amount)); 
    }
}

