<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GithubUserService
{
    public function firstOrCreate(array $data): ?User
    {
        $email = $data['email'];
        $avatar = $data['avatar'];
        $username = $data['username'];
        $githubId = $data['github_id'];
        $githubUrl = $data['github_url'];

        $userWithSameEmail = User::query()
            ->where('email', $email)
            ->first();

        if ($userWithSameEmail && is_null($userWithSameEmail->email_verified_at)) {
            return null;
        }

        if ($userWithSameEmail && $userWithSameEmail->hasVerifiedEmail()) {
            $userWithSameEmail->update([
                'avatar' => $avatar,
                'github_id' => $githubId,
                'github_url' => $githubUrl,
            ]);

            return $userWithSameEmail;
        }

        if (User::query()->where('username', $username)->exists()) {
            return null;
        }

        return User::query()
            ->create([
                'username' => $username,
                'avatar' => $avatar,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random()),
                'github_id' => $githubId,
                'github_url' => $githubUrl,
            ]);
    }
}
