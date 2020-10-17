<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\activity;
use App\qna;
use App\User;

class activityController extends Controller
{
    //

    public function notif(Request $req){
        if(Auth::id()){

                  
            $skip = 0;
            $take = 15;

            if($req->page > 1){
                $skip = $take * $req->page-1;
            }


            $act = activity::
                leftJoin("qnas as quest","quest.id","activities.quest_id")
                ->leftJoin("users as user","activities.user_id","user.id")
                ->where("quest.user_id",Auth::id())
                ->Where("activities.tipe","!=",4)
                ->Where("activities.tipe","!=",0)
                ->orWhere("activities.mention","like",Auth::id())
                ->select("activities.*","quest.text","user.avatar","user.username")
                ->skip($skip)->take($take)
                ->orderBy("activities.created_at","DESC")
                ->get();

            $res  = $act
                ->map(function ($da){
                    $balas = qna::find($da->quest_balas_id);
                    if($balas){
                        $u = User::find($balas->user_id);
                        $da->avatar = $u->avatar;
                        $da->username = $u->username;
                        $da->balasan = $balas->text;
                    }
                    $da->total = activity::where("quest_id",$da->quest_id)->where("tipe",$da->tipe)->count();
                    return $da;
            });

            return $res;
        }
    }
}
