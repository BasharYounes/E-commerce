<?php

namespace App\Services\Likes;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivities;
use App\Models\Adv;

class LikeAnAdvCommandService
{
    public function addLike(array $data): UserActivities
    {

        $data['user_id'] = Auth::id();
        $data['activity_type'] = 'like';
        
        $like = DB::transaction(function () use ($data) {
            return UserActivities::create($data);
        });
        return $like;
    }

    public function removeLike(UserActivities $activity): void
    {
        DB::transaction(function () use ($activity) {
            $activity->delete();
        });
    }

    public function addView(Adv $ad)
    {
        $data = [];
        $data['user_id'] = Auth::id();
        $data['activity_type'] = 'view';
        $data['adv_id'] = $ad->id;
        $view = DB::transaction(function () use ($data) {
            return UserActivities::create($data);
        });
        return $view;
    }

}
