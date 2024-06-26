<?php

namespace App\Http\Resources\Api\V1\Task;

use App\Enums\UserTaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'summary' => $this->summary,
            'image' => $this->image,
            'difficulty' => $this->difficulty_label,
            'stacks' => $this->relationLoaded('stacks') ? $this->stacks->map(function ($stack) {
                return [
                    'id' => $stack->id,
                    'slug' => $stack->slug,
                    'name' => $stack->name
                ];
            }) : [],
            'tags' => $this->relationLoaded('tags') ? $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'slug' => $tag->slug,
                    'name' => $tag->name
                ];
            }) : [],
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];

        if ($this->pivot && $this->pivot->status) {
            $data['status'] = UserTaskStatusEnum::labelFromOption($this->pivot->status);
        }

        if ($this->relationLoaded('solutions')) {
            $solution = $this->solutions->first();

            $data['rating'] = $solution['rating'] ?? null;
            $data['comment'] = $solution['comment'] ?? null;
        }

        return $data;
    }
}
