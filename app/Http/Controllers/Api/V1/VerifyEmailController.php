<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true
            ]);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'success' => true
        ]);
    }
}
