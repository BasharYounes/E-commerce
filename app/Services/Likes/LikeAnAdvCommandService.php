<?php

namespace App\Services\Likes;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeAnAdvCommandService
{
    public function addLike(array $data): Like
    {
        $data['user_id'] = Auth::id();
        $like = DB::transaction(function () use ($data) {
            return Like::create($data);
        });
        return $like;
    }

    public function removeLike(Like $like): void
    {
        DB::transaction(function () use ($like) {
            $like->delete();
        });
    }


}
