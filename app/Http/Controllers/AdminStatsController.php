<?php

namespace App\Http\Controllers;

use App\Models\Adv;
use App\Models\AuthunticateRequest;
use App\Models\Evaluation;
use App\Models\Follow;
use App\Models\Report;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    /**
     * الحصول على إحصائيات Dashboard مع إمكانية التخزين المؤقت والتصفية الزمنية
     */
    public function getDashboardStats(Request $request)
    {
        $period = $request->input('period', 'all');
        
        $cacheKey = 'dashboard_stats_' . $period;
        
        $cacheDuration = 300;
        
        $stats = Cache::remember($cacheKey, $cacheDuration, function () use ($period) {
            return $this->calculateStats($period);
        });
        
        return response()->json($stats);
    }
    
    /**
     * حساب الإحصائيات بناءً على الفترة المحددة
     */
    private function calculateStats($period)
    {
        $startDate = $this->getStartDateByPeriod($period);
        
        return [
            'users' => $this->getUserStats($startDate),
            'ads' => $this->getAdStats($startDate),
            'evaluations' => $this->getEvaluationStats($startDate),
            'reports' => $this->getReportStats($startDate),
            'verification_requests' => $this->getVerificationRequestStats($startDate),
            'recent_activity' => $this->getRecentActivity($startDate),
            'period' => $period,
            'generated_at' => now()->toDateTimeString()
        ];
    }
    
    /**
     * تحديد تاريخ البدء بناءً على الفترة المحددة
     */
    private function getStartDateByPeriod($period)
    {
        switch ($period) {
            case 'today':
                return Carbon::today();
            case 'week':
                return Carbon::today()->subDays(7);
            case 'month':
                return Carbon::today()->subDays(30);
            case 'quarter':
                return Carbon::today()->subDays(90);
            case 'year':
                return Carbon::today()->subDays(365);
            case 'all':
            default:
                return Carbon::createFromDate(2000, 1, 1); // تاريخ قديم جداً للحصول على كل البيانات
        }
    }
    
    /**
     * إحصائيات المستخدمين
     */
    private function getUserStats($startDate)
    {
        return [
            'total' => User::count(),
            'new' => User::where('created_at', '>=', $startDate)->count(),
            'verified' => User::where('is_verified', true)->count(),
            'banned' => User::where('is_banned', true)->count(),
        ];
    }
    
    /**
     * إحصائيات الإعلانات
     */
    private function getAdStats($startDate)
    {
        return [
            'total' => Adv::count(),
            'active' => Adv::where('is_active', true)->count(),
            'inactive' => Adv::where('is_active', false)->count(),
            'new' => Adv::where('created_at', '>=', $startDate)->count(),
        ];
    }
    
    /**
     * إحصائيات التقييمات
     */
    private function getEvaluationStats($startDate)
    {
        return [
            'total' => Evaluation::count(),
        ];
    }
    
    /**
     * إحصائيات التفاعل
     */

    /**
     * إحصائيات البلاغات
     */
    private function getReportStats($startDate)
    {
        return [
            'total' => Report::count(),
            'unviewed' => Report::where('is_view', false)->count(),
        ];
    }
    
    /**
     * إحصائيات المتابعات
     */
    
    /**
     * إحصائيات طلبات التوثيق
     */
    private function getVerificationRequestStats($startDate)
    {
        return [
            'pending' => AuthunticateRequest::where('status', 'pending')->count(),
            'approved' => AuthunticateRequest::where('status', 'approved')->count(),
            'rejected' => AuthunticateRequest::where('status', 'rejected')->count(),
        ];
    }
    
    /**
     * النشاط الحديث
     */
    private function getRecentActivity($startDate)
    {
        return [
            'new_users' => User::withCount(['advs' => function($q) {
                $q->where('is_active', true);
            }])
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(),
            'new_ads' => Adv::with(['user:id,name', 'category:id,name'])
                ->where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }
    
    /**
     * مسح التخزين المؤقت للإحصائيات
     */
    public function clearStatsCache()
    {
        $periods = ['today', 'week', 'month', 'quarter', 'year', 'all'];
        
        foreach ($periods as $period) {
            Cache::forget('dashboard_stats_' . $period);
        }
        
        return response()->json(['message' => 'تم مسح التخزين المؤقت للإحصائيات']);
    }
}
