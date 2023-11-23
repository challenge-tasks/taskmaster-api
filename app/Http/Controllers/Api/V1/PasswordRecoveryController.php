<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ErrorTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RecoverPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordRecoveryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/password-recovery",
     *     tags={"Password recovery"},
     *     summary="Request password recover notification",
     *     @OA\Parameter(
     *          description="Email",
     *          in="query",
     *          name="email",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="admin@gmail.com", value="admin@gmail.com", summary="admin@gmail.com"),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function sendRecoveryNotification(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'exists:users,email']
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        $token = Str::random(100);

        $user->password_recovery_token = $token;
        $user->save();

        $user->sendPasswordResetNotification($token);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/password-recovery",
     *     tags={"Password recovery"},
     *     summary="Recover password",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass credentials",
     *          @OA\JsonContent(
     *              required={"email", "password", "token"},
     *              @OA\Property(property="email", type="string", example="admin@gmail.com"),
     *              @OA\Property(property="password", type="int", example="new_password"),
     *              @OA\Property(property="token", type="int", example="T1SdKvb89QF5QQruKRhtOVp4Uo18RlIyBiUzUOPg9"),
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
    public function recoverPassword(RecoverPasswordRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->input('email'))
            ->where('password_recovery_token', $request->input('token'))
            ->first();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'type' => ErrorTypeEnum::UNAUTHORIZED
            ], 401);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->sendPasswordRecoveredNotification();

        return response()->json([
            'success' => true
        ]);
    }
}
