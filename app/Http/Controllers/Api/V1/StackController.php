<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StackController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tech-stacks/search",
     *     tags={"Tech stacks"},
     *     summary="Search for stack",
     *     @OA\Parameter(
     *          description="Search query",
     *          in="query",
     *          name="q",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="html", value="html", summary="html"),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required'
        ]);

        $q = $request->input('q');

        $stacks = Stack::query()
            ->select(['id', 'slug', 'name'])
            ->where('name', 'LIKE', '%' . $q . '%')
            ->orWhere('slug', 'LIKE', '%' . $q . '%')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $stacks
        ]);
    }
}
