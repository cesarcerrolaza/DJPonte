<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class QueueableVerifyEmail extends VerifyEmailNotification implements ShouldQueue
{
    use Queueable;
}
