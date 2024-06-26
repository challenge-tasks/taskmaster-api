<?php

namespace App\Http\Resources\Api\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'is_email_verified' => isset($this->email_verified_at),
            'github_url' => $this->github_url,
            'last_confirmation_notification_sent_at' => $this->last_confirmation_notification_sent_at
                ? strtotime($this->last_confirmation_notification_sent_at)
                : null,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];
    }
}
