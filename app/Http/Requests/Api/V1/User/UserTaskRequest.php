<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->username === $this->route('user');
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'This action is unauthorized.'
        ], 403));
    }
}
