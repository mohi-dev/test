<?php

namespace App\Models;

use App\Traits\TimeStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cat extends Model
{
    use HasFactory, TimeStamps, SoftDeletes;

    const CREATED_AT = 'time_created';
    const UPDATED_AT = 'time_updated';

    protected $fillable = [
        'title', 'description', 'pic',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
