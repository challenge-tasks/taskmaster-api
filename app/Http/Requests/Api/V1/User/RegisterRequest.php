<?php

namespace App\Http\Requests\Api\V1\User;

use App\Helpers\ValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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

    protected function failedValidation(Validator $validator)
    {
        $failedRules = $validator->failed();

        throw new HttpResponseException(response()->json([
            'message' => 'Unprocessable Content.',
            'type' => ValidationHelper::getErrorType($failedRules)
        ], 422));
    }
}
