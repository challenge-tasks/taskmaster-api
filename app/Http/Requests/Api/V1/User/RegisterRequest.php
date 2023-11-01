<?php

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100']
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
