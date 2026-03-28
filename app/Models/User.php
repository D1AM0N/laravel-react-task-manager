<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Requirement #1: Allow registration data to be saved.
     * These fields MUST be defined here to avoid the 500 MassAssignmentException.
     */
   protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin', // Add this!
];
    /**
     * Keep sensitive data hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** * Requirement #4: Link the User to their Tasks 
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}