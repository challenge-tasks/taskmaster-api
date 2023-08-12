<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'username',
        'avatar',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function (User $user) {
            $hash = md5($user->email);
            $user->avatar = 'https://www.gravatar.com/avatar/' . $hash . '?d=identicon&s=100';
        });

        static::updating(function (User $user) {
            if ($user->isDirty('email')) {
                $hash = md5($user->email);
                $user->avatar = 'https://www.gravatar.com/avatar/' . $hash . '?d=identicon&s=100';
            }
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

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withPivot('status');
    }
}
