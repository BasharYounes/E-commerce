<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    use ApiResponse;

    public function __construct(
       public UserRepository $userRepository
    ){}
    public function follow($id)
    {
        $user = $this->userRepository->findById($id);
            
        auth()->user()->follow($user);
        
        return $this->success('following Successfully');
    }

    public function unfollow($id)
    {
        $user = $this->userRepository->findById($id);

        auth()->user()->unfollow($user);
            
        return $this->success('Unfollowing Successfully');
    }
}
