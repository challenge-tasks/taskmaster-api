<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatusEnum;
use App\Enums\UserTaskStatusEnum;
use App\Events\UserTaskDeletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Solution\StoreSolutionRequest;
use App\Http\Requests\Api\V1\User\UserTaskRequest;
use App\Http\Resources\Api\V1\Task\TaskListResource;
use App\Models\User;
use App\Services\Solution\SolutionService;
use App\Services\Task\TaskQueryService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class UserTaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users/{username}/tasks",
     *     tags={"User tasks"},
     *     summary="Tasks list",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Username",
     *          in="path",
     *          name="username",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="johndoe", value="johndoe", summary="johndoe"),
     *     ),
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
     *          description="Filter. You can pass multiple values like ?difficulty=1,2,3",
     *          in="query",
     *          name="filter",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="difficulty", type="int", example="1"),
     *              @OA\Property(property="tech_stacks", type="int", example="1"),
     *              @OA\Property(property="tags", type="int", example="1"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function index(UserTaskRequest $request, string $username): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 25);

        $user = User::query()->where('username', $username)->firstOrFail();

        $tasks = $user->tasks()
            ->with([
                'stacks',
                'tags',
                'solutions' => fn(HasMany $query) => $query->where('user_id', $user->id)
            ])
            ->where('tasks.status', TaskStatusEnum::PUBLISHED);

        $tasks = TaskQueryService::filter($tasks)->paginate($perPage);

        return TaskListResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/{username}/tasks",
     *     tags={"User tasks"},
     *     summary="Add task",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Username",
     *          in="path",
     *          name="username",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="johndoe", value="johndoe", summary="johndoe"),
     *     ),
     *     @OA\Parameter(
     *          description="Task ID",
     *          in="query",
     *          name="task_id",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="task_id", type="int", example="1"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function store(UserTaskRequest $request, string $username): JsonResponse
    {
        $request->validate([
            'task_id' => ['required', 'exists:tasks,id']
        ]);

        $user = Cache::remember('user_' . $username, now()->addHour(), function () use ($username) {
            return User::query()->where('username', $username)->firstOrFail();
        });

        $user->tasks()->sync($request->input('task_id'), false);

        return response()->json([
            'data' => [
                'status' => UserTaskStatusEnum::IN_DEVELOPMENT->label()
            ]
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{username}/tasks/{taskSlug}",
     *     tags={"User tasks"},
     *     summary="Delete task",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Username",
     *          in="path",
     *          name="username",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="johndoe", value="johndoe", summary="johndoe"),
     *     ),
     *     @OA\Parameter(
     *          description="Task slug",
     *          in="path",
     *          name="taskSlug",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="first-task", value="first-task", summary="first-task"),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function destroy(UserTaskRequest $request, string $username, string $taskSlug): JsonResponse
    {
        $user = Cache::remember('user_' . $username, now()->addHour(), function () use ($username) {
            return User::query()->where('username', $username)->firstOrFail();
        });

        $task = Cache::remember($user->username . '_task_' . $taskSlug, now()->addHour(), function () use ($user, $taskSlug) {
            return $user->tasks()->where('slug', $taskSlug)->firstOrFail();
        });

        $user->tasks()->detach($task);

        UserTaskDeletedEvent::dispatch($user->id, $task->id);

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/{username}/tasks/{taskSlug}/solutions",
     *     tags={"User tasks"},
     *     summary="Upload task solution",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          description="Username",
     *          in="path",
     *          name="username",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="johndoe", value="johndoe", summary="johndoe"),
     *     ),
     *     @OA\Parameter(
     *          description="Task slug",
     *          in="path",
     *          name="taskSlug",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="first-task", value="first-task", summary="first-task"),
     *     ),
     *     @OA\Parameter(
     *          description="File (zip/rar)",
     *          in="query",
     *          name="file",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="file", type="int", example="solution.zip"),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function storeSolution(StoreSolutionRequest $request, SolutionService $service, string $username, string $taskSlug): JsonResponse
    {
        $user = Cache::remember('user_' . $username, now()->addHour(), function () use ($username) {
            return User::query()->where('username', $username)->firstOrFail();
        });

        $task = Cache::remember($user->username . '_task_' . $taskSlug, now()->addHour(), function () use ($user, $taskSlug) {
            return $user->tasks()->where('slug', $taskSlug)->firstOrFail();
        });

        if ($request->has('url')) {
            $successfullySaved = $service->saveSolutionWithURL($request->input('url'), $user->id, $task->id);
        } else {
            $successfullySaved = $service->uploadSolution($request->file('file'), $user->id, $task->id);
        }

        if ($successfullySaved) {
            $user->tasks()->updateExistingPivot($task, [
                'status' => UserTaskStatusEnum::REVIEWING->value
            ]);
        }

        return response()->json([
            'success' => true
        ], 201);
    }
}
