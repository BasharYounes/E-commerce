<?php

namespace App\Listeners;

use App\Events\AdPublishedEvent;
use App\Services\Notifications\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyFollowersOfNewAd
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
    public function handle(AdPublishedEvent $event): void
    {
        $publisher = $event->publisher;
        $ad = $event->ad;
        
        $followers = $publisher->followers;
        
        if ($followers->isEmpty()) {
            return;
        }
        
        $notificationService = new NotificationService();
        
        foreach ($followers as $follower) {
            $notificationService->send(
                user: $follower,
                type: 'new_ad_from_following',
                data: [
                    'publisher_name' => $publisher->name,
                    'ad_title' => $ad->description,
                    'ad_id' => $ad->id
                ]
            );
        }
    }
}
