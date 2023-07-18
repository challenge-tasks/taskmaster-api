<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DifficultyEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Task\TaskListResource;
use App\Http\Resources\Api\V1\Task\TaskResource;
use App\Models\Stack;
use App\Models\Tag;
use App\Models\Task;
use App\Services\Task\TaskQueryService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     tags={"Tasks catalog"},
     *     summary="Tasks list",
     *     @OA\Parameter(
     *          description="Page",
     *          in="query",
     *          name="page",
     *          required=false,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="1", value="1", summary="1"),
     *     ),
     *     @OA\Parameter(
     *          description="Tasks per page",
     *          in="query",
     *          name="per_page",
     *          required=false,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="20", value="20", summary="20"),
     *     ),
     *     @OA\Parameter(
     *          description="Sorting (By default: -id)",
     *          in="query",
     *          name="sort",
     *          required=false,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="id", value="id", summary="id"),
     *          @OA\Examples(example="-id", value="-id", summary="-id"),
     *          @OA\Examples(example="name", value="name", summary="name"),
     *          @OA\Examples(example="-name", value="-name", summary="-name"),
     *          @OA\Examples(example="difficulty", value="difficulty", summary="difficulty"),
     *          @OA\Examples(example="-difficulty", value="-difficulty", summary="-difficulty"),
     *          @OA\Examples(example="created_at", value="created_at", summary="created_at"),
     *          @OA\Examples(example="-created_at", value="-created_at", summary="-created_at"),
     *          @OA\Examples(example="updated_at", value="updated_at", summary="updated_at"),
     *          @OA\Examples(example="-updated_at", value="-updated_at", summary="-updated_at")
     *     ),
     *     @OA\Parameter(
     *          description="Filter. You can pass multiple values like ?filter[difficulty]=1,2,3",
     *          in="query",
     *          name="filter",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="filter[difficulty]", type="int", example="1"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $query = Task::published()->latest('updated_at');

        $tasks = TaskQueryService::filter($query)->paginate($perPage);

        return TaskListResource::collection($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/filter",
     *     tags={"Tasks catalog"},
     *     summary="Filters for tasks catalog",
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function filter()
    {
        $difficulties = DifficultyEnum::filterOptions();

        $stacks = Stack::selectRaw('slug as value, name as label, hex')
            ->whereHas('tasks')
            ->limit(10)
            ->get();

        $tags = Tag::selectRaw('slug as value, name as label')
            ->whereHas('tasks')
            ->limit(10)
            ->get();

        $data = [
            'difficulties' => [
                'key' => 'difficulty',
                'label' => 'Сложность',
                'items' => $difficulties
            ],
            'tech_stacks' => [
                'key' => 'tech_stacks',
                'label' => 'Стек технологий',
                'items' => $stacks
            ],
            'tags' => [
                'key' => 'tags',
                'label' => 'Теги',
                'items' => $tags
            ]
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{slug}",
     *     tags={"Task page"},
     *     summary="Task detailed info",
     *     @OA\Parameter(
     *          description="Task slug",
     *          in="path",
     *          name="slug",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="first-task", value="first-task", summary="first-task"),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function show(string $slug)
    {
        $task = Task::where('slug', $slug)->firstOrFail();

        return TaskResource::make($task);
    }
}
