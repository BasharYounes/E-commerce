<?php

namespace App\Repositories;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeRepository
{
    public function findLike($id): Like
    {
        return Like::where('adv_id', $id)
                ->where('user_id', Auth::id())
                ->first();
    }
}
