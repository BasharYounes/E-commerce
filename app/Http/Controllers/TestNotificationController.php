<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestNotificationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * إرسال إشعار تجريبي للمستخدم الحالي
     */
    public function sendTestNotification(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->fcm_token) {
            return $this->error('المستخدم لا يملك FCM token', 400);
        }

        try {
            $this->notificationService->send($user, 'create Adv', [
                'user_name' => $user->name
            ]);

            return $this->success('تم إرسال الإشعار التجريبي بنجاح');
        } catch (\Exception $e) {
            return $this->error('فشل إرسال الإشعار: ' . $e->getMessage(), 500);
        }
    }

    /**
     * إرسال إشعار لجميع المستخدمين
     */
    public function sendNotificationToAll(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        $users = User::whereNotNull('fcm_token')->get();
        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            try {
                $this->notificationService->send($user, 'create Adv', [
                    'title' => $request->title,
                    'body' => $request->body
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                \Log::error("فشل إرسال إشعار للمستخدم {$user->id}: " . $e->getMessage());
            }
        }

        return $this->success('تم إرسال الإشعارات', [
            'success_count' => $successCount,
            'fail_count' => $failCount,
            'total_users' => $users->count()
        ]);
    }
}
