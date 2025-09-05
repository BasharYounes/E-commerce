<?php 

namespace App\Repositories\BanUser;

use App\Models\Ban;

class BanUserRepository
{
    public function createBan($data)
    {
        return Ban::create($data);
    }

    public function updateDateBan($userId)
    {
        Ban::where('user_id', $userId)
            ->active()
            ->update(['banned_until' => now()]);
    }

    public function getAllBanUsers()
    {
        return Ban::where('banned_until','>', now())->orWhere('is_permanent',true);
    }
}