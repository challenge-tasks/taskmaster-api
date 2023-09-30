<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'image'
    ];

    protected static function booted(): void
    {
        static::deleted(function (TaskImage $taskImage) {
            if ($taskImage->image && Storage::exists($taskImage->image)) {
                Storage::delete($taskImage->getOriginal('image'));
            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
