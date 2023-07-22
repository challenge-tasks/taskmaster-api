<?php

namespace Database\Seeders;

use App\Models\Stack;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    public function run(): void
    {
        Stack::factory(10)->create();
        Tag::factory(10)->create();
        Task::factory(30)->create();

        foreach (Task::cursor() as $task) {
            $ids = [rand(1, 10), rand(1, 10), rand(1, 10)];

            $task->stacks()->sync($ids);
            $task->tags()->sync($ids);
        }

        $user = User::where('username', 'user')->first();
        $user->tasks()->sync(range(1, 15));
    }
}
