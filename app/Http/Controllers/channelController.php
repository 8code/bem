<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\event as MetDa;
use App\qna;
use App\qna_follow;
use App\join_event;
use Auth;
use App\User;
use Ramsey\Uuid\Uuid;

class channelController extends Controller
{
    //
    public function index($id){
        $user_id = join_event::where("event_id",$id)->pluck("user_id")->toArray();

        $user = User::whereIn("id",$user_id)->paginate(10);

        return $user;


    }
}
