<?php

namespace App\Http\Resources\Api\V1\Task;

use App\Enums\UserTaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'summary' => $this->summary,
            'image' => $this->image,
            'images' => $this->images && count($this->images) ? $this->images->pluck('image') : null,
            'difficulty' => $this->difficulty_label,
            'description' => $this->details?->description,
            'stacks' => $this->stacks->map(function ($stack) {
                return [
                    'id' => $stack->id,
                    'slug' => $stack->slug,
                    'name' => $stack->name
                ];
            }),
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'slug' => $tag->slug,
                    'name' => $tag->name
                ];
            }),
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];

        if ($this->pivot && $this->pivot->status) {
            $data['status'] = UserTaskStatusEnum::labelFromOption($this->pivot->status);
        }

        return $data;
    }
}
