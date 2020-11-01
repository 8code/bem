<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    //
    public function user_1()
    {
        return $this->hasOne('App\User', 'id', 'user_1');
    }
    public function user_2()
    {
        return $this->hasOne('App\User', 'id', 'user_2');
    }
    public function event()
    {
        return $this->hasOne('App\Event', 'id', 'event_id');
    }
}
