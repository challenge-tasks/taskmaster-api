<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! hash_equals((string) $this->user()->getKey(), (string) $this->input('id'))) {
            return false;
        }

        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->input('hash'))) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
