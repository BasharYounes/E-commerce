<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteRepository
{
    public function findFavorite($id): Favorite
    {
        return Favorite::where('adv_id', $id)
                ->where('user_id', Auth::id())
                ->first();
    }

    public function findFavoritesByUserId($userId)
    {
        return Favorite::with('adv')->where('user_id', $userId)->get();
    }
}
