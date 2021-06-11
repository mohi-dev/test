<?php

namespace App\Models;

use App\Traits\TimeStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TimeStamps, SoftDeletes;

    const CREATED_AT = 'time_created';
    const UPDATED_AT = 'time_updated';

    protected $fillable = [
        'user_name', 'password'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
}
