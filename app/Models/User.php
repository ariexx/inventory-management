<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the login activities for the user.
     */
    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    /**
     * Get the user level.
     *
     * @return string
     */
    public function getLevelAttribute(): string
    {
        return match ($this->attributes['level']) {
            'admin' => 'admin',
            'manager' => 'manager',
            'staff' => 'staff',
            default => 'Unknown',
        };
    }

    public function is_admin(): bool
    {
        return $this->level === 'admin';
    }

    public function is_manager(): bool
    {
        return $this->level === 'manager';
    }

    public function is_staff(): bool
    {
        return $this->level === 'staff';
    }
}
