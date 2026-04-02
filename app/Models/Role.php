<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Relationship: Roles can be assigned to many Users.
     * Pivot Table: role_user
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relationship: Roles have many Permissions.
     * Pivot Table: permission_role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Helper: Check if this specific role has a permission.
     */
    public function hasPermission($permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Boot Logic: Automatically format the slug from the name if missing.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }
}