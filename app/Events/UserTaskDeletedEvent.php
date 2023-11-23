<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTaskDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public int $taskId;

    public function __construct(int $userId, int $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }
}
