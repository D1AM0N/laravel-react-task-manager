<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'otp_code', 
        'otp_expires_at', 
        'is_otp_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'is_otp_verified' => 'boolean',
        ];
    }

    /** --- RBAC LOGIC --- **/

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission($permissionSlug): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    public function hasRole($role): bool
    {
        // Support checking multiple roles at once via array or single string
        if (is_array($role)) {
            return $this->roles->pluck('slug')->intersect($role)->isNotEmpty();
        }
        return $this->roles->where('slug', $role)->isNotEmpty();
    }

    /** --- TASK LOGIC --- **/

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}