<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Http\Requests\BanUser;
use App\Repositories\BanUser\BanUserRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;


class BanController extends Controller
{
    use ApiResponse;
    public function __construct(
        public UserRepository $userRepository,
        public BanUserRepository $banUserRepository,
    ){}

    public function banUser(BanUser $request, $userId)
    {
        $user = $this->userRepository->findById($userId);

        $ban = $this->banUserRepository->createBan($request->validated());

        $this->userRepository->update($user,[
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => $request->reason
        ]);

        if($request->is_permanent)
        {
            GenericNotificationEvent::dispatch($user,'Permenant_Ban',[]);
        }
        else{
            GenericNotificationEvent::dispatch($user,'Ban_Until',['until_date' => $request->banned_until,]);
        }

        $this->userRepository->deleteUserToken($user);

        return $this->success('تم حظر المستخدم بنجاح', $ban);
    }

    public function unbanUser($userId)
    {
        $user = $this->userRepository->findById($userId);

        $this->userRepository->update($user,[
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null
        ]);
        
        $this->banUserRepository->updateDateBan($userId);
            
        GenericNotificationEvent::dispatch($user,'Un_Ban',[]);
        
        return $this->success('تم إلغاء حظر المستخدم بنجاح');
    }

    public function getBanUsers()
    {
        $ban_users = $this->banUserRepository->getAllBanUsers();

        return $this->success('المستخدمين المحظورين', $ban_users);
    }
}
