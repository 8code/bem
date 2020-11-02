<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\channel;
use App\User;
use App\messages;
use App\read_messages;
use App\join_event;
use Ramsey\Uuid\Uuid;
use Auth;


class messageController extends Controller
{
    //
    public function index(){
        // Message Me
        if(Auth::id()){
            
            $eventId = join_event::
            where("user_id",Auth::id())
            ->pluck("event_id")
            ->toArray();

            $chat = channel::
                where("user_1",Auth::id())
                ->orWhere("user_2",Auth::id())
                ->orWhereIn("event_id",$eventId)
                ->orderBy("last_active","DESC")
                ->with("event")
                ->take(30)
                ->get();

            $res = [];

            foreach($chat as $c){
                if($c->event_id){
                    
                    // Last Message
                    $message = messages::where("channel_id",$c->id)
                    ->latest()->first();
    
                    $readAt = read_messages::where("user_id",Auth::id())
                    ->where("channel_id",$c->id)->first();
    
                    if($readAt){
                        $notread = messages::where("channel_id",$c->id)
                        ->where("created_at",">",$readAt->at)
                        ->count();
                    }else{
                        $notread = messages::where("channel_id",$c->id)
                        ->count();
                    }
                    
    
    
                    $c->notread = $notread;
                    $c->message = $message;
                    
                    array_push($res, $c);
                }else{
                   
                    if($c->user_1 == Auth::id()){
                        $user = User::find($c->user_2);
                    }else{
                        $user = User::find($c->user_1);
                    }
                    // Last Message
                    $message = messages::where("channel_id",$c->id)
                    ->latest()->first();
    
                    $readAt = read_messages::where("user_id",Auth::id())
                    ->where("channel_id",$c->id)->first();
    
                    if($readAt){
                        $notread = messages::where("channel_id",$c->id)
                        ->where("created_at",">",$readAt->at)
                        ->count();
                    }else{
                        $notread = messages::where("channel_id",$c->id)
                        ->count();
                    }
                    
    
    
                    $c->notread = $notread;
                    $c->user = $user;
                    $c->anonim = $c->anonim;
                    $c->message = $message;

                    
                    array_push($res, $c);
                    
                }
               

            };


             return $res;


        }
        return "";
    }

    public function chatRoom($id){
        if(Auth::id()){
            $metda =  channel::where("room_id",$id)->with("event")->first();
            
            if($metda){
                if($metda->user_1 == Auth::id()){
                    $user = User::find($metda->user_2);
                }else if($metda->user_2 == Auth::id()){
                    $user = User::find($metda->user_1);
                }else{
                    $user = "";
                }
    
                // get Last Message
                $message = messages::where("channel_id",$metda->id)
                ->with("user")
                ->latest()
                ->paginate(10);
    
                return [
                    "channel_id"=> $metda->id,
                    "user"=> $user,
                    "event"=> $metda->event,
                    "message"=> $message,
                    "anonim"=> $metda->anonim,
                ];
            }
                    
           
        }
    }
    public function chatTo(Request $req, $id){
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

                if($req->anonim){
                    $chat->anonim = Auth::id().",".$id;
                }
               
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
            if($req->img){
                    
                    $image_64 = $req->img; //your base64 encoded data
            
                    $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
            
                    // find substring fro replace here eg: data:image/png;base64,
            
                    $image = str_replace($replace, '', $image_64); 
            
                    $image = str_replace(' ', '+', $image); 
            
                    $imageName = Auth::id() ."/". time().'.jpg';
            
                    Storage::disk('public')->put($imageName, base64_decode($image));

                    $m->img = env("APP_URL")."/storage/".$imageName;

                    $m->thumb = "";
                }
                if($req->audio){
                

                    $replace = substr($req->audio, 0, strpos($req->audio, ',')+1); 
            
                    // find substring fro replace here eg: data:image/png;base64,
            
                    $audio = str_replace($replace, '', $req->audio); 
            
                    $audio = str_replace(' ', '+', $audio); 
            
                    $audioName = Auth::id() ."/audio/". time().'.mp3';
            
                    Storage::disk('public')->put($audioName, base64_decode($audio));

                    $m->audio = env("APP_URL")."/storage/".$audioName;

                }
             if($req->stiker){
                $m->stiker = $req->stiker;
             }
            $m->save();

            $channel = channel::find($id);
            $channel->last_active = $m->created_at;
            $channel->save();

            $this->readMessage(Auth::id(),$id);

            return $m;
        }
      
    }

    public function readMessage($user_id, $channel_id){
        if(Auth::id()){

            $r = read_messages::
                where("user_id",$user_id)
                ->where("channel_id",$channel_id)
                ->first();
            if(!$r){
                $r =  new read_messages;
                $r->user_id = $user_id;
                $r->channel_id = $channel_id;
                $r->at = now();
                $r->save();
            }else{
                $r->user_id = $user_id;
                $r->channel_id = $channel_id;
                $r->at = now();
                $r->save();
            }
          

            return $r;
        }
      
    }
}
