<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\PasswordRecoveredNotification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasName
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'username',
        'avatar',
        'email',
        'email_verified_at',
        'password',
        'github_id',
        'github_url',
        'last_confirmation_notification_sent_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (is_null($user->avatar)) {
                $hash = md5($user->email);
                $user->avatar = 'https://www.gravatar.com/avatar/' . $hash . '?d=identicon&s=100';
            }
        });

        static::updating(function (User $user) {
            if ($user->isDirty('email')) {
                if (is_null($user->github_id)) {
                    $hash = md5($user->email);
                    $user->avatar = 'https://www.gravatar.com/avatar/' . $hash . '?d=identicon&s=100';
                }

                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            Cache::delete('profile_' . $user->id);
        });

        static::deleting(function (User $user) {
            $user->solutions()->withTrashed()->each(fn(Solution $solution) => $solution->forceDelete());
        });
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN->value);
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EmailVerificationNotification());

        $this->last_confirmation_notification_sent_at = now();
        $this->saveQuietly();
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function sendPasswordRecoveredNotification(): void
    {
        $this->notify(new PasswordRecoveredNotification());
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withPivot('status');
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }
}
