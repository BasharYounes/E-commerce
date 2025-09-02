<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Adv;
use App\Models\Follow;
use App\Models\Notification;
use App\Events\AdPublishedEvent;
use App\Listeners\NotifyFollowersOfNewAd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowersNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_followers_receive_notifications_when_user_publishes_ad()
    {
        // إنشاء مستخدمين
        $publisher = User::factory()->create(['name' => 'أحمد']);
        $follower1 = User::factory()->create(['name' => 'محمد']);
        $follower2 = User::factory()->create(['name' => 'فاطمة']);
        
        // إنشاء علاقات المتابعة
        Follow::create([
            'follower_id' => $follower1->id,
            'followed_id' => $publisher->id
        ]);
        
        Follow::create([
            'follower_id' => $follower2->id,
            'followed_id' => $publisher->id
        ]);
        
        // إنشاء إعلان
        $ad = Adv::create([
            'description' => 'إعلان تجريبي',
            'price' => 100,
            'location' => 'القاهرة',
            'phone' => '0123456789',
            'category_id' => 1,
            'user_id' => $publisher->id
        ]);
        
        // إرسال Event
        $event = new AdPublishedEvent($ad, $publisher);
        $listener = new NotifyFollowersOfNewAd();
        $listener->handle($event);
        
        // التحقق من إرسال الإشعارات
        $this->assertDatabaseHas('notifications', [
            'user_id' => $follower1->id,
            'title' => 'إعلان جديد من أحمد',
            'body' => 'قام أحمد بنشر إعلان جديد: إعلان تجريبي'
        ]);
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $follower2->id,
            'title' => 'إعلان جديد من أحمد',
            'body' => 'قام أحمد بنشر إعلان جديد: إعلان تجريبي'
        ]);
    }
    
    public function test_no_notifications_sent_when_user_has_no_followers()
    {
        // إنشاء مستخدم بدون متابعين
        $publisher = User::factory()->create(['name' => 'أحمد']);
        
        // إنشاء إعلان
        $ad = Adv::create([
            'description' => 'إعلان تجريبي',
            'price' => 100,
            'location' => 'القاهرة',
            'phone' => '0123456789',
            'category_id' => 1,
            'user_id' => $publisher->id
        ]);
        
        // إرسال Event
        $event = new AdPublishedEvent($ad, $publisher);
        $listener = new NotifyFollowersOfNewAd();
        $listener->handle($event);
        
        // التحقق من عدم إرسال إشعارات
        $this->assertDatabaseMissing('notifications', [
            'title' => 'إعلان جديد من أحمد'
        ]);
    }
}
