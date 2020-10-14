<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\qna;
use App\User;
use App\qna_follow;
use App\group;
use App\group_follow;
use App\user_follow;
use Auth;


class searchController extends Controller
{
    public function quest(Request $req,$keyword){
        if($keyword){
             
            $skip = 0;
            $take = 6;

            if($req->page > 1){
                $skip = $take * $req->page-1;
            }


            $metda = qna::
                    with("group")
                    ->with("user")
                    ->with("quest")
                    ->where("text","like","%".$keyword."%")
                    // ->orderBy("activity","DESC")
                    ->orderBy("id","DESC")
                    ->take(100)
                    ->get();


        
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
            
        });

        

        $respons = [
            "data" => $metda->skip($skip)->take($take)->toArray(),
            "total"=> $metda->skip($skip)->take($take)->count()
        ];


        return json_encode($respons);
       


        }
    }

    public function group(Request $req, $keyword)
    {
    
        $skip = 0;
        $take = 10;

        if($req->page > 1){
            $skip = $take * $req->page-1;
        }



        $metda = group::
             where("name","like","%".$keyword."%")
            ->orWhere("username","like","%".$keyword."%")
            ->orderBy("last_active","DESC")
            ->take(100)
            ->get();
        


        $res = $metda->map(function($g) {

            $qna = qna::where("group_id",$g->id)->count();
            $g->total_qna = $qna;
            
            $follow = group_follow::
                where("user_id",Auth::id())
                ->where("group_id",$g->id)
                ->first();
        
            if($follow){
                $g->followed = true;
            }
            return $g;
            
        });

        $respons = [
            "data" => $res->skip($skip)->take($take)->toArray(),
            "total"=> $res->skip($skip)->take($take)->count()
        ];


        return json_encode($respons);

    }
    public function user(Request $req, $keyword)
    {
    
        $skip = 0;
        $take = 10;

        if($req->page > 1){
            $skip = $take * $req->page-1;
        }



        $metda = user::
             where("name","like","%".$keyword."%")
            ->orWhere("username","like","%".$keyword."%")
            ->skip($skip)->take($take)
            // ->orderBy("last_active","DESC")
            ->get();
        


        $res = $metda->map(function($g) {
            
            $follow = user_follow::
                where("user_id",Auth::id())
                ->where("followed_id",$g->id)
                ->first();
        
            if($follow){
                $g->followed = true;
            }

            return $g;
            
        });

        return response()->json($res);
    }
    //
}
