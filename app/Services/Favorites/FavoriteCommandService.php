<?php

namespace App\Services\Favorites;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteCommandService
{
    public function addToFavorite(array $data): Favorite
    {
        $data['user_id'] = Auth::id();
        $favorite = DB::transaction(function () use ($data) {
            return Favorite::create($data);
        });
        return $favorite;
    }

    public function removeFromFavorite(Favorite $favorite): void
    {
        DB::transaction(function () use ($favorite) {
            $favorite->delete();
        });
    }


}
