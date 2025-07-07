<?php

namespace App\Jobs;

use App\Models\Djsession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteDjsessionJob implements ShouldQueue
{
    use Queueable;

    protected $djsession;

    /**
     * Create a new job instance.
     */
    public function __construct(Djsession $djsession)
    {
        $this->djsession = $djsession;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->djsession->image && $this->djsession->image !== Djsession::getDefaultImagePath()) {
            Storage::disk('public')->delete($this->djsession->image);
        }
        $this->djsession->delete();
    }
}
