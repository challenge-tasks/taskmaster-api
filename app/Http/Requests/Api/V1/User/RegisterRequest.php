<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:16']
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        return array_merge($validated, [
            'password' => Hash::make($validated['password'])
        ]);
    }
}
