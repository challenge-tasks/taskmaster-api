<?php

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'username' => ['required', 'string', 'unique:users,username,' . $user->id],
            'email' => ['required', 'unique:users,email,' . $user->id]
        ];
    }
}
