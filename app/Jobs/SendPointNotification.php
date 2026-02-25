<?php

namespace App\Jobs;

use App\Mail\PointEarned;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendPointNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public Mailable $mailable,
    )
    {  }

    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send($this->mailable);
    }
}
