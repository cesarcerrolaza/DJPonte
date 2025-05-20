<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tip;
use Illuminate\Support\Facades\Log;

class TipsList extends Component
{
use WithPagination;

    public $newTips = [];
    public $djsessionId;

    public function mount($djsessionId)
    {
        $this->djsessionId = $djsessionId;;
    }

    public function getListeners()
    {
        return [
            "echo-private:djsession.{$this->djsessionId},NewTip" => 'prependNewTip',
        ];
    }

    public function prependNewTip($payload)
    {
        Log::info('New tip received (List)', $payload);
        $tip = Tip::with('user', 'song')
            ->where('id', $payload['tip_id'])
            ->where('status', 'paid')
            ->first();

        if ($tip) {
            $this->newTips[] = $this->formatTip($tip);

            // Asegúrate de que los nuevos van primero
            $this->newTips = collect($this->newTips)
                ->sortByDesc('updated_at')
                ->values()
                ->toArray();
        }
    }

    private function formatTip(Tip $tip)
    {
        return [
            'id' => $tip->id,
            'user_name' => $tip->user->name,
            'amount' => $tip->formatted_amount,
            'song_name' => $tip->custom_title ?? optional($tip->song)->title,
            'song_artist' => $tip->custom_artist ?? optional($tip->song)->artist,
            'description' => $tip->description,
            'updated_at' => $tip->updated_at,
        ];
    }


    public function render()
    {
        $paginatedTips = Tip::where('djsession_id', $this->djsessionId)
            ->where('status', 'paid')
            ->orderByDesc('updated_at')
            ->paginate(10);

        $mappedPaginatedTips = $paginatedTips->map(fn($tip) => $this->formatTip($tip));


        return view('livewire.tips.tip-list', [
            'tips' => collect($this->newTips)->merge($mappedPaginatedTips),
            'pagination' => $paginatedTips, // por si necesitas los links de paginación
        ]);
    }
}
