<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Solution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'file',
        'is_checked'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Solution $solution) {
            if ($solution->file && Storage::exists($solution->file)) {
                Storage::delete($solution->getOriginal('file'));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
