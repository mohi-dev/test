<?php


namespace App\Traits;

trait TimeStamps
{
    public function initializeTimeStamps()
    {
        $this->dateFormat = 'U';
        $this->casts['time_created'] = 'datetime:U';
        $this->casts['time_updated'] = 'datetime:U';
        $this->casts['deleted_at'] = 'datetime:U';
    }
}
