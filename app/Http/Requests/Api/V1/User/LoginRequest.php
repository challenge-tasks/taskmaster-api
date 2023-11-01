<?php

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100']
        ];
    }
}
