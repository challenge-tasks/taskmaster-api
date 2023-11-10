<?php

namespace Tests\Feature;

use App\Enums\UserTaskStatusEnum;
use App\Models\Task;
use Tests\TestCase;

class UserTaskTest extends TestCase
{
    public function test_user_can_get_own_tasks(): void
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->get('/api/v1/users/user/tasks');

        $response->assertStatus(200);
    }

    public function test_user_can_add_task()
    {
        $token = $this->getBearerToken();
        $task = Task::query()->first();

        $response = $this->withToken($token)->post('/api/v1/users/user/tasks', [
            'task_id' => $task->id
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_get_one_task()
    {
        $token = $this->getBearerToken();

        $tasks = $this->withToken($token)->get('/api/v1/users/user/tasks');
        $taskSlug = $tasks['data'][0]['slug'];

        $response = $this->withToken($token)->get('/api/v1/users/user/tasks/' . $taskSlug);

        $response->assertStatus(200);
    }

    public function test_user_can_update_task()
    {
        $token = $this->getBearerToken();

        $tasks = $this->withToken($token)->get('/api/v1/users/user/tasks');
        $taskSlug = $tasks['data'][0]['slug'];

        $response = $this->withToken($token)->put('/api/v1/users/user/tasks/' . $taskSlug, [
            'status' => UserTaskStatusEnum::DONE->value
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_delete_task()
    {
        $token = $this->getBearerToken();

        $tasks = $this->withToken($token)->get('/api/v1/users/user/tasks');
        $taskSlug = $tasks['data'][0]['slug'];

        $response = $this->withToken($token)->delete('/api/v1/users/user/tasks/' . $taskSlug);

        $response->assertStatus(204);
    }

    public function test_user_can_get_task_statuses()
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->get('/api/v1/users/user/tasks/statuses');

        $response->assertStatus(200);
    }
}
