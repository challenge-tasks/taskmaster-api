<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UpdateProfileRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     summary="Get profile",
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function show()
    {
        $user = Auth::user();

        return UserResource::make($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     summary="Update profile",
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
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        return UserResource::make($user);
    }
}
