<?php

namespace App\Models;

use App\Traits\JsonData;
use App\Traits\TimeStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use TimeStamps, SoftDeletes, JsonData;

    const CREATED_AT = 'time_created';
    const UPDATED_AT = 'time_updated';


    protected $json_data = ['data',];
    protected $fillable = ['id', 'pass', 'data',];

    static function getWithPass($pass)
    {
        return self::where('pass', $pass)->first();
    }

    public function setData($key, $value)
    {
        $this->data->{$key} = $value;
        return $this->save();
    }

    public function getData($key, $default = null)
    {
        return $this->data->get($key, $default);
    }

    public function forgetData($key)
    {
        $this->data->forget($key);
        return $this->save();
    }
}

