<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    //
    protected $guarded = [];

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
