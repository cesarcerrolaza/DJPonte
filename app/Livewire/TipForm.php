<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tip;
use App\Models\Djsession;
use App\Livewire\SongSearchable;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TipForm extends Component
{
    use SongSearchable;

    public Djsession $djsession;
    public $songName = '';
    public $artistName = '';
    public $songSuggestions = [];
    public $selectedSong = null;
    public float $amount = 0.50; // valor inicial
    public $description = '';
    protected $rules = [
        'amount' => 'required|numeric|min:0.50',
        'description' => 'nullable|string|max:255',
        'songName' => 'nullable|string|max:255',
        'artistName' => 'nullable|string|max:255',
        'selectedSong' => 'nullable|exists:songs,id',
    ];
    public $lastTip = null;

    public function mount(Djsession $djsession)
    {
        $this->djsession = $djsession;
        $this->lastTip = Tip::where('djsession_id', $this->djsession->id)
            ->where('status', 'paid')
            ->with('user')
            ->orderByDesc('updated_at')
            ->first();
        if ($this->lastTip) {
            $this->lastTip = [
                'user_name' => $this->lastTip->user->name,
                'amount' => $this->lastTip->formatted_amount,
                'song_name' => $this->lastTip->custom_title ?? $this->lastTip->song->title,
                'song_artist' => $this->lastTip->custom_artist ?? $this->lastTip->song->artist,
                'description' => $this->lastTip->description,
                'updated_at' => $this->lastTip->updated_at,
            ];
        }
    }


    public function submit()
    {
        $this->validate($this->rules);
        $tip = Tip::create([
            'user_id'        => Auth::id(),
            'dj_id'          => $this->djsession->user_id,
            'djsession_id'   => $this->djsession->id,
            'song_id'        => $this->selectedSong,
            'custom_title'   => $this->songName,
            'custom_artist'  => $this->artistName,
            'amount'         => intval($this->amount * 100),
            'currency'       => 'eur',
            'status'         => 'pending',
            'description'    => $this->description,
        ]);

        // Configura la API key de Stripe
        Stripe::setApiKey(config('cashier.secret'));
        $user = Auth::user();
        if ($user->stripe_id === null) {
           $user->createAsStripeCustomer();
        }
        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $tip->amount,
                    'product_data' => [
                        'name' => "Propina para {$this->djsession->name}",
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'tip_id' => $tip->id,
            ],
            'customer_email' => $user->email,
            'success_url' => route('tips.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('tips.cancel'),
        ]);
        $tip->update(['stripe_session_id' => $session->id]);
        return redirect()->away($session->url);
    }

    public function getListeners()
    {
        return [
            "echo-private:djsession.{$this->djsession->id},NewTip" => 'handleNewTip',
        ];
    }

    public function handleNewTip($payload)
    {
        Log::info('NewTip payload (TipForm)', $payload);
        if ($payload['status'] === 'paid') {
            $tip = Tip::where('id', $payload['tip_id'])->with('user', 'song')->first();

            $this->lastTip = [
                'user_name' => $tip->user->name,
                'amount' => $tip->formatted_amount,
                'song_name' => $tip->custom_title ?? $tip->song->title,
                'song_artist' => $tip->custom_artist ?? $tip->song->artist,
                'description' => $tip->description,
                'updated_at' => $tip->updated_at,
            ];
        }
    }

    public function render()
    {
        return view('livewire.tips.tip-form');
    }
}
