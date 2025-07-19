<?php

namespace App\Http\Controllers;

use App\Events\UserResetPasswordEvent;
use App\Exceptions\CodeSendingException;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\TokenAndPasswordRequest;
use Hash;
use App\Repositories\UserRepository;
use App\Services\PasswordReset;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\PersonalAccessToken;
use Str;


class ForgetPasswordController extends Controller
{
    use ApiResponse;


    public function __construct(
    protected PasswordReset $passwordReset,
    protected UserRepository $userRepository
    )
    {}

    public function forgotPassword(EmailRequest $request) 
    {
            $user = $this->userRepository->findByEmail($request->email);
    
            $token = $this->userRepository->createToken($user);
                        
            $resetLink = url("/api/reset-password?token=$token");
            
            if (!event(new UserResetPasswordEvent($user,$resetLink))) {
                throw new CodeSendingException('فشل إرسال الرابط');
            }
            
        return $this->success('تم إرسال رابط الاستعادة إلى بريدك',$resetLink);
    }

    public function resetPassword(TokenAndPasswordRequest $request) 
    {
            $token = $request->query('token');

            $accessToken = $this->userRepository->findToken($token);

            if (!$accessToken) {
                return $this->error('توكن غير صالح أو منتهي الصلاحية', 400);
            }
            
            $user = $accessToken->tokenable;

            $this->userRepository->update($user,['password' => Hash::make($request->password)]);

           $this->userRepository->deleteUserToken($user);

        return $this->success( 'تم تحديث كلمة المرور بنجاح');
    }
}