<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Models\Ban;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


class BanController extends Controller
{
    use ApiResponse;

    public function banUser(Request $request, $userId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'banned_until' => 'nullable|date|after:now',
            'is_permanent' => 'boolean',
        ]);

        $user = User::findOrFail($userId);

        $ban = Ban::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'banned_until' => $request->banned_until,
            'is_permanent' => $request->is_permanent ?? false,
        ]);

        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => $request->reason
        ]);

        if($request->is_permanent)
        {
            GenericNotificationEvent::dispatch($user,'Permenant_Ban',[]);
        }
        else{
            GenericNotificationEvent::dispatch($user,'Ban_Until',['until_date' => $request->banned_until,]);
        }

        $user->tokens()->delete();

        return $this->success('تم حظر المستخدم بنجاح', $ban);
    }

    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        
        $user->update([
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null
        ]);
        
        Ban::where('user_id', $userId)
            ->active()
            ->update(['banned_until' => now()]);
            
        GenericNotificationEvent::dispatch($user,'Un_Ban',[]);
        
        return $this->success('تم إلغاء حظر المستخدم بنجاح');
    }

    public function getUserBans($userId)
    {
        $user = User::findOrFail($userId);
        $bans = $user->bans()->with('bannedBy')->get();
        
        return $this->success('تاريخ حظورات المستخدم', $bans);
    }
}
