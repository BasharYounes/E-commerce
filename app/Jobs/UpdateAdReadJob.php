<?php

namespace App\Jobs;

use App\Models\Adv;
use App\Models\AdvRead; // تأكد من وجود هذا الاستيراد
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAdReadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $action,
        public Adv $ad
    ) {}

    public function handle()
    {
        if ($this->action === 'deleted') {
            AdvRead::where('id', $this->ad->id)->delete();
            return;
        }
        
        AdvRead::updateOrCreate(
            ['id' => $this->ad->id],
            [
                'image' => $this->ad->image,
                'price' => $this->ad->price,
                'location' => $this->ad->location,
                'views_count' => $this->ad->views_count,
                'interactions_count' => $this->ad->interactions_count,
                'category_id' => $this->ad->category_id, 
                'published_duration' => $this->ad->published_duration,
                'description' => $this->ad->description,
                'is_active' => $this->ad->is_active,
                'user_id' => $this->ad->user_id,
            ]
        );
    }
}