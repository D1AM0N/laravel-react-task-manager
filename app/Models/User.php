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
        'name', 'email', 'password', 'otp_code', 'otp_expires_at', 'is_otp_verified'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'is_otp_verified' => 'boolean',
        ];
    }

    /** --- RBAC LOGIC (Optimized for Cloud) --- **/

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // New optimized permissions relationship
    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class);
    }

    public function hasPermission($permissionSlug): bool
    {
        // SPEED FIX: If roles and permissions are already loaded, check memory
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(function ($role) use ($permissionSlug) {
                return $role->permissions->contains('slug', $permissionSlug);
            });
        }

        // FALLBACK: Only hits Aiven if not pre-loaded
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    public function hasRole($role): bool
    {
        if ($this->relationLoaded('roles')) {
            $roles = is_array($role) ? $role : [$role];
            return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
        }
        
        return is_array($role) 
            ? $this->roles()->whereIn('slug', $role)->exists() 
            : $this->roles()->where('slug', $role)->exists();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}