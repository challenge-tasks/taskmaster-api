<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // TODO: Add spatie/laravel-query-builder for filtering.
        $tasks = Task::published()
            ->latest('updated_at')
            ->paginate(25);

        return response()->json([
            'data' => $tasks
        ]);
    }

    public function show(Task $task)
    {
        return response()->json([
            'data' => $task
        ]);
    }
}
