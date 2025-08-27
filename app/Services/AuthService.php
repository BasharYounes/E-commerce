<?php

namespace App\Services;

use App\Exceptions\RegistrationFailedException;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function registerUser($request)
    {

        $data = $request->only(['name', 'email', 'phone', 'password']);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $filename = time() . '.' . $request->file('image')->extension();
            $path = Storage::disk('public')->putFileAs(
                'users',
                $request->file('image'),
                $filename,
                ['visibility' => 'public']
            );
            $data['image'] = $path;
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if (!$user) {
            throw new RegistrationFailedException();
        }

        return $user;
    }

    public function checkPassword(string $password, string $hashPassword)
    {
        if (!Hash::check($password, $hashPassword)) {
            throw new AuthenticationException();
        }
    }

    public function storeFCM(User $user,string $fcm_token)
    {
        $user->update(['fcm_token' => $fcm_token]);
    }
}
