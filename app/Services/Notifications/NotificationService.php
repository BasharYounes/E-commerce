<?php

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationService
{
    public function send(User $user, string $type, array $data = []): void
    {
        $content = $this->getTemplate($type, $data);

        $this->sendFCM($user, $content);

        $this->saveToDatabase($user, $content);
    }

    private function getTemplate(string $type, array $data): array
    {
        $template = config("notifications.templates.$type");

        return [
            'title' => $this->replacePlaceholders($template['title'], $data),
            'body' => $this->replacePlaceholders($template['body'], $data)
        ];    
    }

    protected function replacePlaceholders(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }

    private function sendFCM(User $user, array $content): void
    {
        try {
            $messaging = app('firebase.messaging');
            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification($content);
            $messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('فشل إرسال الإشعار: ' . $e->getMessage());
        }
    }
    

    private function saveToDatabase(User $user, array $content): void
    {
        Notification::create([
            'user_id' => $user->id,
            'title' => $content['title'],
            'body' => $content['body']
        ]);
    }
}