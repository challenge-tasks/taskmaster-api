<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'name' => Str::ucfirst($this->faker->word),
            'hex' => $this->faker->hexColor
        ];
    }
}
