<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qna extends Model
{
    //
    protected $guarded = [];


    public function group()
    {
        return $this->hasOne('App\group', 'id', 'group_id');
    }
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function quest()
    {
        return $this->hasOne('App\qna', 'id', 'quest_id');
    }

}
