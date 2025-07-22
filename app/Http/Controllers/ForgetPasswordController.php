<?php

namespace App\Http\Controllers;

use App\Events\UserResetPasswordEvent;
use App\Exceptions\CodeSendingException;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\CodeAndPasswordRequest;
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
    
            $code = $this->codeService->generateCode($user);

            event(new UserRegistered($user, $code)) ; 
            
        return $this->success('تم إرسال رابط الاستعادة إلى بريدك',$resetLink);
    }

    public function resetPassword(CodeAndPasswordRequest $request) 
    {
        $storedCode = $this->casheService->getCodeFromCashe($user);

        if (!$storedCode || $storedCode != $request->code) {
           throw new InvalidCodeException();
        }

            $this->userRepository->update($user,['password' => Hash::make($request->password)]);

            $this->casheService->forgetCodeFromCashe($user);

           $this->userRepository->deleteUserToken($user);

        return $this->success( 'تم تحديث كلمة المرور بنجاح');
    }
}