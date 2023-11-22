<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RecoverPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email'],
            'token' => ['required', 'exists:users,password_recovery_token'],
            'password' => ['required', 'string', 'min:8', 'max:100']
        ];
    }
}
