<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ErrorTypeEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\LoginRequest;
use App\Http\Requests\Api\V1\User\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Auth"},
     *     summary="Log in",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="admin@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456789"),
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
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized.',
                'type' => ErrorTypeEnum::INCORRECT_PASSWORD
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json([
            'data' => [
                'user' => UserResource::make($user),
                'token' => $token
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Auth"},
     *     summary="Registration",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"first_name", "email", "password"},
     *              @OA\Property(property="username", type="string", example="johndoe"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="password", type="string", example="pass@word"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Bad request"
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::query()->create($credentials);
        $token = $user->createToken('MyApp')->accessToken;

        $user->assignRole(RoleEnum::USER->value);

        return response()->json([
            'data' => [
                'user' => UserResource::make($user),
                'token' => $token
            ]
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Auth"},
     *     summary="Log out",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Bad request"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::user()->token()->revoke();

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/github/login",
     *     tags={"Auth"},
     *     summary="Log in via GitHub",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="username", type="string", example="johndoe"),
     *              @OA\Property(property="email", type="string", example="admin@gmail.com"),
     *              @OA\Property(property="github_id", type="int", example="42"),
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
    public function loginViaGithub(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required',
            'github_id' => 'required'
        ]);

        $user = User::query()
            ->where('github_id', $request->input('github_id'))
            ->first();

        if (! $user) {
            $user = User::query()
                ->create([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => Hash::make(Str::random()),
                    'github_id' => $request->input('github_id'),
                ]);
        }

        return response()->json([
            'data' => [
                'user' => UserResource::make($user),
                'token' => $user->createToken('MyApp')->accessToken
            ]
        ]);
    }
}
