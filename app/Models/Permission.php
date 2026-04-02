<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Permission extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Relationship: Permissions can belong to many Roles.
     * Pivot Table: permission_role
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Boot Logic: Automatically format the slug from the name if missing.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($permission) {
            if (empty($permission->slug)) {
                $permission->slug = Str::slug($permission->name);
            }
        });
    }
}