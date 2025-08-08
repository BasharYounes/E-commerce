<?php

namespace App\Repositories;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivities;

class LikeRepository
{
    public function findLike($id): UserActivities
    {
        return UserActivities::where('adv_id', $id)
                ->where('user_id', Auth::id())
                ->where('activity_type', 'like')
                ->first();
    }
}
