<?php

namespace App\Listeners;

use App\Events\GenericNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Notifications\NotificationService;

class HandleGenericNotification
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
    public function handle(GenericNotificationEvent $event): void
    {
        $service = new NotificationService();
        $service->send(
            user: $event->user,
            type: $event->type,
            data: $event->data
        );
    }
}
