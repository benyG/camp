<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Notification as Notif;
use App\Notifications\NewMail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $mail;
    protected $para;
    protected $opt;
    /**
     * Create a new job instance.
     */
    public function __construct(User $User, string $ma, array $pa, string $op)
    {
        $this->user = $User;
        $this->mail = $ma;
        $this->para = $pa;
        $this->opt = $op;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notif::send($this->user, new NewMail($this->mail,$this->para,$this->opt));
    }
}
