<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('!$UW7FEQ$8wZpxRL')
            ]
        );

        $user = User::updateOrCreate(
            ['username' => 'user'],
            [
                'email' => 'user@gmail.com',
                'password' => Hash::make('password')
            ]
        );

        $admin->assignRole(RoleEnum::ADMIN->value);
        $user->assignRole(RoleEnum::USER->value);
    }
}
