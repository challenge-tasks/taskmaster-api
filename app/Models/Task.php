<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'summary',
        'image',
        'status',
        'difficulty'
    ];

    protected static function booted(): void
    {
        static::saved(function (Task $task) {
            if ($task->isDirty('image')) {
                Storage::disk('public_uploads')->delete($task->getOriginal('image'));
            }
        });

        static::deleted(function (Task $task) {
            if ($task->image && Storage::disk('public_uploads')->exists($task->image)) {
                Storage::disk('public_uploads')->delete($task->getOriginal('image'));
            }
        });
    }
}
