<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    //
    protected $guarded = [];

    public function channel()
    {
        return $this->hasOne('App\channel', 'event_id', 'id');
    }
}
