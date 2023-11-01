<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->username === $this->route('user');
    }
}
