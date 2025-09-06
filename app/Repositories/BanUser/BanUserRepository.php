<?php 

namespace App\Repositories\BanUser;

use App\Models\Ban;

class BanUserRepository
{
    public function createBan($data ,$id)
    {
        $data['user_id'] = $id;
        return Ban::create($data);
    }

    public function updateDateBan($userId)
    {
        Ban::where('user_id', $userId)
            ->active()
            ->update([
            'banned_until' => now(),
            'is_permanent' => false    
        ]);
    }

    public function getAllBanUsers()
    {
        return Ban::with('user:id,name')->where('banned_until','>', now())->orWhere('is_permanent',true);
    }
}