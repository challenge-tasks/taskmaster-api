<?php

namespace App\Listeners;

use App\Events\UserTaskDeletedEvent;
use App\Models\Solution;

class DeleteUserTaskSolution
{
    public function handle(UserTaskDeletedEvent $event): void
    {
        $userId = $event->userId;
        $taskId = $event->taskId;

        $solution = Solution::query()
            ->where('user_id', $userId)
            ->where('task_id', $taskId)
            ->first();

        $solution?->delete();
    }
}
