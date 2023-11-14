<?php

namespace App\Models;

use App\Enums\DifficultyEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Task extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'slug',
        'name',
        'summary',
        'image',
        'status',
        'difficulty'
    ];

    protected static function booted(): void
    {
        static::updated(function (Task $task) {
            if ($task->isDirty('image') && Storage::exists($task->getOriginal('image'))) {
                Storage::delete($task->getOriginal('image'));
            }
        });

        static::deleting(function (Task $task) {
            $task->details()->delete();
            $task->images()->each(fn(TaskImage $taskImage) => $taskImage->delete());
        });

        static::deleted(function (Task $task) {
            if ($task->image && Storage::exists($task->image)) {
                Storage::delete($task->getOriginal('image'));
            }
        });
    }

    public function details(): HasOne
    {
        return $this->hasOne(TaskDetail::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TaskImage::class);
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function stacks(): BelongsToMany
    {
        return $this->belongsToMany(Stack::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', TaskStatusEnum::PUBLISHED->value);
    }

    public function getDifficultyLabelAttribute(): string
    {
        $difficulties = DifficultyEnum::options();

        return $difficulties[$this->difficulty];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }

    public function isBelongsToUser(): bool
    {
        $userId = Auth::guard('api')->id();

        return $this->users()->where('user_id', $userId)->exists();
    }
}
