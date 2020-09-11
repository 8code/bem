<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\group_follow;
use App\qna;
use App\qna_follow;

class profileController extends Controller
{

    public function profile($id){
        return User::where("username",ltrim($id, '@'))->first();
    }
    
    public function quest(Request $req, $id){
        // return $id;
        if(Auth::id()){
                
                    $skip = 0;
                    $take = 5;

                    if($req->page > 1){
                        $skip = $take * $req->page-1;
                    }

               

                    if($req->filter){
                        if($req->filter == 'Quest Only'){
                            $filterType = " quest_id is null";
                        }else if($req->filter == 'Quest & Balasan'){
                            $filterType = 'audio != ""';
                        }else if($req->filter == 'Media'){
                            $filterType = 'audio != ""';
                        }else{
                            $filterType = 'id';
                        }
                    }else{
                        $filterType = 'id ';
                    }

                    
                    $metda = qna::orderBy("id","DESC")
                        ->whereRaw($filterType)
                        ->where("user_id",$id)
                        ->with("group")
                        ->with("user")
                        ->with("quest")
                        ->skip($skip)->take($take)
                        ->get();


                    if($metda){
                    $metda->map(function($q) {

                        if($q->quest){

                            $q->membalas_user = User::find($q->quest->user_id)->username;
                        }

                        
                        $follow = qna_follow::
                            where("user_id",Auth::id())
                            ->where("quest_id",$q->id)
                            ->first();

                        if($follow){
                            $q->followed = true;
                        }

                        return $q;
                        
                    })->toArray();
                }
                    $respons = [
                        "data" => $metda
                    ];

                    return response()->json($respons);
        }
    }
}
