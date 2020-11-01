<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\user_follow;
use App\group_follow;
use Auth;

class helperController extends Controller
{
    //
    public function listProdi(){
        $res = User::distinct("study_program")->where("study_program","!=","")->pluck("study_program");
        return $res;
    }
    public function listUniv(){
        $res = User::distinct("university")->where("university","!=","")->pluck("university");
        return $res;
    }

    public function fixFollowMyAccount(){
     

        // if(Auth::user()->id == 1){
            $allUser = User::all();
            foreach($allUser as $user){
                
                $cek = user_follow::where("user_id",$user->id)->where("followed_id",$user->id)->first();
                if(!$cek){
                    $follow = new user_follow;
                    $follow->user_id = $user->id;
                    $follow->followed_id = $user->id;
                    $follow->save();
    
                }
    
                $cek2 = user_follow::where("user_id",$user->id)->where("followed_id",6)->first();
                if(!$cek){
                    $follow2 = new user_follow;
                    $follow2->user_id = $user->id;
                    $follow2->followed_id = 1;
                    $follow2->save();
    
                }
                $group_id = [2,82,83,84,85,86,87,69];

                foreach($group_id as $id){
                    $cek3 = group_follow::where("user_id",$user->id)->where("group_id",$id)->first();
                     
                    if(!$cek3){
                        $follow3 = new group_follow;
                        $follow3->user_id = $user->id;
                        $follow3->group_id = $id;
                        $follow3->save();
                    }
                }

               
            }
        // }
    }
}
