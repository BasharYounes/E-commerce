<?php

namespace App\Services;

use App\Exceptions\RegistrationFailedException;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function registerUser(array $data) {
        // dd($data);

        $data['password'] = Hash::make($data['password']);

        $user =User::create($data);

         if (!$user) {
                throw new RegistrationFailedException();
            }
        
        return $user;
    }
    
    public function checkPassword(string $password , string $hashPassword)
    {
        if (!Hash::check($password,$hashPassword)) {
            throw new AuthenticationException();
        } 
    }
}