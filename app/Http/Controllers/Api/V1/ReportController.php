<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/reports",
     *     tags={"Reports"},
     *     summary="Send report to telegram group",
     *     @OA\Parameter(
     *          description="Text",
     *          in="query",
     *          name="text",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="text", type="string", example="I found a bug."),
     *          ),
     *     ),
     *     @OA\Response(response="201", description="Success"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="429", description="Too many requests"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function store(Request $request, ReportService $service): JsonResponse
    {
        $request->validate([
            'text' => ['required', 'string']
        ]);

        Report::query()
            ->create([
                'user_id' => Auth::guard('api')->id(),
                'text' => $request->input('text')
            ]);

        $service->sendReportToTelegram($request->input('text'));

        return response()->json([
            'success' => true
        ], 201);
    }
}
