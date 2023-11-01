<?php

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class UserTaskRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Auth::user()->username === $this->route('user');
    }


}
