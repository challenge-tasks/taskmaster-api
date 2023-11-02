<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tags/search",
     *     tags={"Tags"},
     *     summary="Search for tag",
     *     @OA\Parameter(
     *          description="Search query",
     *          in="query",
     *          name="q",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="backend", value="backend", summary="backend"),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required'
        ]);

        $q = $request->input('q');

        $tags = Tag::query()
            ->select(['id', 'slug', 'name'])
            ->where('name', 'LIKE', '%' . $q . '%')
            ->orWhere('slug', 'LIKE', '%' . $q . '%')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $tags
        ]);
    }
}
