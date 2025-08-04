<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ihs',
                'email' => '123123@gmail.com',
                'password' => Hash::make('@123123'),
            ]
        ];

        User::insert($users);
    }
}
