<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isBanned()) {
            $ban = auth()->user()->activeBan();
            
            $message = 'تم حظر حسابك.';
            
            if ($ban->is_permanent) {
                $message .= ' الحظر دائم.';
            } else {
                $message .= ' الحظر حتى ' . $ban->banned_until->format('Y-m-d H:i:s');
            }
            
            if ($ban->reason) {
                $message .= ' السبب: ' . $ban->reason;
            }
            
            // إلغاء المصادقة وإرجاع خطأ
            // auth()->logout();
            
            return response()->json([
                'error' => $message
            ], 403);
        }
        return $next($request);
    }
}
