<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $images = [
            'test/1.jpg',
            'test/2.jpg',
            'test/3.jpg'
        ];

        return [
            'slug' => $this->faker->slug,
            'name' => $this->faker->name,
            'summary' => $this->faker->text(250),
            'image' => $images[rand(0, 2)],
            'status' => TaskStatusEnum::PUBLISHED->value,
            'difficulty' => rand(1, 5)
        ];
    }
}
