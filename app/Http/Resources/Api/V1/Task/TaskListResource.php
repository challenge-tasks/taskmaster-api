<?php

namespace App\Http\Resources\Api\V1\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'summary' => $this->summary,
            'image' => $this->image,
            'difficulty' => $this->difficulty_label,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
