<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     summary="Get profile",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function show(): UserResource
    {
        $user = Auth::user();

        return UserResource::make($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     summary="Update profile",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Request body",
     *          in="query",
     *          name="Request body",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="username", type="johndoe", example="johndoe"),
     *              @OA\Property(property="email", type="john@doe.com", example="john@doe.com"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function update(Request $request): UserResource
    {
        $user = Auth::user();

        $data = $request->validate([
            'username' => ['required', 'string', 'unique:users,username,' . $user->id],
            'email' => ['required', 'unique:users,email,' . $user->id]
        ]);

        $user->update($data);

        return UserResource::make($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/profile/password",
     *     tags={"Profile"},
     *     summary="Update password",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Request body",
     *          in="query",
     *          name="Request body",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="password", type="qwerty123", example="qwerty123"),
     *              @OA\Property(property="password_confirmation", type="qwerty123", example="qwerty123"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function updatePassword(Request $request): UserResource
    {
        $request->validate([
            'password' => ['required', 'min:8', 'max:100', 'confirmed']
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return UserResource::make($user);
    }
}
