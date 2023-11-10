<?php

namespace App\Http\Requests\Api\V1\Solution;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSolutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->username === $this->route('user');
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:rar,zip']
        ];
    }
}
