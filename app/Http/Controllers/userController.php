<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user_follow;
use App\activity;
use Auth;

class userController extends Controller
{
    //

    public function follow($id){
        if(Auth::id()){
            $cek = user_follow::where("user_id",Auth::id())->where("followed_id",$id)->first();
            if(!$cek){
                $follow = new user_follow;
                $follow->user_id = Auth::id();
                $follow->followed_id = $id;
                $follow->save();

                $dataAct = [
                    "user_id" => Auth::id(),
                    "tipe" => 6,
                ];
                
                activity::create($dataAct);

            }
        }
    }
}
