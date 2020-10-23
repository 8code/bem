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

            $filter = [];

            $likeCheck = [];
            
            foreach($act as $da){
                $balas = qna::find($da->quest_balas_id);

                if($balas){
                    $u = User::find($balas->user_id);
                    $da->avatar = $u->avatar;
                    $da->username = $u->username;
                    $da->balasan = $balas->text;
                    $da->audio = $balas->audio;
                    $da->for_balasan = [
                        "id"=> $balas->id,
                        "user"=> [
                            "username"=> $u->username
                        ],
                    ];
                }

                $da->total = activity::where("quest_id",$da->quest_id)->where("tipe",$da->tipe)->count();

                if($da->tipe == 1){
                    
                    // cek filter like
                    if(in_array($da->quest_id, $likeCheck)){
                        // return "tess";
                    }else{
                        array_push($likeCheck, $da->quest_id);  
                                              
                        array_push($filter, $da);
                    }

                }else{
                    array_push($filter, $da);
                }
               
            }

            return $filter;
        }
    }
}
