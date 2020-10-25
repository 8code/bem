<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\channel;
use App\User;
use App\messages;
use Ramsey\Uuid\Uuid;
use Auth;


class messageController extends Controller
{
    //
    public function index(){
        // Message Me
        if(Auth::id()){
            
            $chat = channel::
                where("user_1",Auth::id())
                ->orWhere("user_2",Auth::id())
                ->get();

                $res = $chat->map(function($c) {
                    if($c->user_1 == Auth::id()){
                        $user = User::find($c->user_2);
                    }else{
                        $user = User::find($c->user_1);
                    }
                    // Last Message
                    $message = messages::where("channel_id",$c->id)->latest()->first();

                    $c->user = $user;
                    $c->message = $message;
                    
                    return $c;

                });


                return $res;


        }
        return "";
    }

    public function chatRoom($id){
        if(Auth::id()){
            $metda =  channel::where("room_id",$id)->first();
            
            if($metda){
                if($metda->user_1 == Auth::id()){
                    $user = User::find($metda->user_2);
                }else if($metda->user_2 == Auth::id()){
                    $user = User::find($metda->user_1);
                }else{
                    return "";
                }
    
                // get Last Message
                $message = messages::where("channel_id",$metda->id)
                ->with("user")
                ->latest()
                ->paginate(10);
    
                return [
                    "channel_id"=> $metda->id,
                    "user"=> $user,
                    "message"=> $message
                ];
            }
                    
           
        }
    }
    public function chatTo($id){
        // Message Me
        if(Auth::id()){
            
            // cek   
            $cek = channel::
                where("user_1",Auth::id())
                ->where("user_2",$id)
                ->first();
            
            $cek2 = channel::
                where("user_1",$id)
                ->where("user_2", Auth::id())
                ->first();

            if($cek || $cek2){
                
                if($cek){
                    return $cek->room_id;
                }
                if($cek2){
                    return $cek->room_id;
                }


            }else{
                $chat = new channel;
                $chat->user_1 = Auth::id();
                $chat->user_2 = $id;
                $chat->room_id = Uuid::uuid1();
                $chat->save();

                return $chat->room_id;
            }
           
            return "";

        }
        return "";
    }


    public function sendMessage(Request $req, $id){
        if(Auth::id()){
            $m = new messages;
            $m->channel_id = $id;
            $m->user_id = Auth::id();
            $m->text = $req->text;
            if($req->image){
               $m->image = $req->image;
            }
            if($req->audio){
                $m->audio = $req->audio;
            }
             if($req->stiker){
                $m->stiker = $req->stiker;
             }
            $m->save();

            return $m;
        }
      
    }
}
