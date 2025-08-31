<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\SongRequest;
use App\Models\Tip;

class DjDashboard extends Component
{
    public $activeSession;
    public $recentActivity;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $dj = Auth::user();
        $this->activeSession = $dj->djsessionActive;
        $this->recentActivity = $this->fetchRecentActivity($dj);
    }

    protected function fetchRecentActivity($dj)
    {
        $latestRequests = SongRequest::whereHas('djsession', function ($query) use ($dj) {
            $query->where('user_id', $dj->id);
        })
        ->with(['song', 'djsession']) 
        ->latest()
        ->take(10)
        ->get();

        $latestTips = Tip::whereHas('djsession', function ($query) use ($dj) {
            $query->where('user_id', $dj->id);
        })
        ->with(['user', 'djsession'])
        ->latest()
        ->take(10)
        ->get();
        
        return $latestRequests
            ->merge($latestTips)
            ->sortByDesc('created_at')
            ->take(5);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dj-dashboard', ['activeSession' => $this->activeSession]);
    }
}