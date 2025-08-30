<?php

namespace App\Repositories;

use App\Models\Adv;
use Illuminate\Support\Facades\DB;

class AdvRepository
{
    public function findAdv($id)
    {
        return Adv::findOrFail($id);
    }

    public function getGeneralRecommendations($limit = 10)
    {
        return Adv::with('user:id,name', 'category:id,name')
            ->where('is_active', 1)
            ->orderBy('interactions_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }


    public function getAdsByCategoriesAndLocations($categoryIds, $locationKeywords, $limit, $excludeUserId = null)
    {
        $query = Adv::with('user:id,name', 'category:id,name')
            ->where('is_active', 1);

        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        if ($categoryIds->isNotEmpty()) {
            $query->whereIn('category_id', $categoryIds);
        }

        if ($locationKeywords->isNotEmpty()) {
            $query->where(function ($q) use ($locationKeywords) {
                foreach ($locationKeywords as $location) {
                    $q->orWhere('location', 'LIKE', "%{$location}%");
                }
            });
        }

        return $query->orderBy('interactions_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getAdditionalAdsByCategories($categoryIds, $excludeIds, $limit, $excludeUserId = null)
    {
        $query = Adv::with('user:id,name', 'category:id,name')
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $excludeIds);

        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

  
    public function getRecommendedAdsForUser($categoryIds)
    {
        return Adv::with('user:id,name', 'category:id,name')
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }

 
    public function getUserPreferredCategories($userId)
    {
        return DB::table('favorites')
            ->join('advs', 'favorites.adv_id', '=', 'advs.id')
            ->select('advs.category_id', DB::raw('COUNT(*) as total'))
            ->where('favorites.user_id', $userId)
            ->groupBy('advs.category_id')
            ->orderByDesc('total')
            ->take(3)
            ->pluck('category_id');
    }

    
    public function getFollowingUsersAds($userId, $adsPerUser = 3, $excludeIds = [])
    {
        $followingUserIds = DB::table('follows')
            ->where('follower_id', $userId)
            ->pluck('followed_id');

        if ($followingUserIds->isEmpty()) {
            return collect();
        }

        $ads = collect();
        
        foreach ($followingUserIds as $followingUserId) {
            $userAds = Adv::with('user:id,name', 'category:id,name')
                ->where('user_id', $followingUserId)
                ->where('is_active', 1)
                ->whereNotIn('id', $excludeIds)
                ->orderBy('created_at', 'desc')
                ->limit($adsPerUser)
                ->get();
                
            $ads = $ads->merge($userAds);
        }

        return $ads->shuffle()->values();
    }

    
}