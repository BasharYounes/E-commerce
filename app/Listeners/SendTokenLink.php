<?php

namespace App\Listeners;

use App\Events\UserResetPassword;
use App\Events\UserResetPasswordEvent;
use App\Mail\ChangePassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendTokenLink
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserResetPasswordEvent $event): void
    {
        Mail::to($event->user->email)
        ->send(new ChangePassword($event->link));
    }
}
