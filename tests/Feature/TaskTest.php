<?php

namespace Tests\Feature;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_can_get_tasks_list(): void
    {
        $response = $this->get('/api/v1/tasks');

        $response->assertStatus(200);
    }

    public function test_can_get_tasks_filter()
    {
        $response = $this->get('/api/v1/tasks/filter');

        $response->assertStatus(200);
    }

    public function test_can_get_one_task()
    {
        $task = Task::query()->first();

        $response = $this->get('/api/v1/tasks/' . $task->slug);

        $response->assertStatus(200);
    }
}
