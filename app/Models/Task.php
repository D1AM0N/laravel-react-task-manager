<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'due_date', 'status'];

    // Eager load the user by default if you find yourself always needing names
    // protected $with = ['user']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}