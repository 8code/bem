<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\activity;

class activityController extends Controller
{
    //

    public function notif(Request $req){
        if(Auth::id()){

                  
            $skip = 0;
            $take = 5;

            if($req->page > 1){
                $skip = $take * $req->page-1;
            }

           // 1 Menyukai Quest, 2 Membalas Quest , 3 Admin Membuat Quest , 4 Memfollow Akun

           $act = activity::
                         leftJoin("qnas as quest","quest.id","activities.quest_user_id")
                         ->leftJoin("users as user","quest.user_id","user.id")
                        ->where("quest.user_id",Auth::id())
                        ->select("activities.*","quest.text","user.avatar")
                        ->skip($skip)->take($take)
                        ->orderBy("id","DESC")
                        ->get();

            $res  = $act
                ->map(function ($da){
                    $da->total = activity::where("quest_user_id",$da->quest_user_id)->count();
                    return $da;
                });

            return $res;
        }
    }
}
