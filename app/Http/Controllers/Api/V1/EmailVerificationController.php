<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ErrorTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/email-verification/verify",
     *     tags={"Email verification"},
     *     summary="Verify the email",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass id & hash",
     *          @OA\JsonContent(
     *              required={"id", "hash"},
     *              @OA\Property(property="id", type="string", example="42"),
     *              @OA\Property(property="hash", type="string", example="f7cbea7aabf0ee72f2fe1925471b743b"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Auth error"
     *     )
     * )
     */
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $user = User::query()->findOrFail($request->input('id'));

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

    /**
     * @OA\Post(
     *     path="/api/v1/email-verification/resend",
     *     tags={"Email verification"},
     *     summary="Resend verifiaction notification",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Auth error"
     *     )
     * )
     */
    public function resend(): JsonResponse
    {
        $user = Auth::user();

        $emailVerificationInterval = config('mail.email_verification_interval');

        if (
            $user->last_confirmation_notification_sent_at
            && Carbon::parse($user->last_confirmation_notification_sent_at)->diffInSeconds(now()) < $emailVerificationInterval
        ) {
            $retryAfter = $emailVerificationInterval - Carbon::parse($user->last_confirmation_notification_sent_at)->diffInSeconds(now());

            return response()->json([
                'message' => $retryAfter,
                'type' => ErrorTypeEnum::TOO_MANY_REQUESTS
            ], 429)
                ->withHeaders([
                    'Retry-After' => $retryAfter
                ]);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
                'type' => ErrorTypeEnum::EMAIL_ALREADY_VERIFIED
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'last_confirmation_notification_sent_at' => strtotime($user->last_confirmation_notification_sent_at)
        ]);
    }
}
