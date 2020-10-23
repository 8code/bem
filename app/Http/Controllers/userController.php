<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user_follow;
use App\group_follow;
use App\activity;
use App\User as Metda;
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

    public function unfollow($id){
        if(Auth::id()){
            $cek = user_follow::where("user_id",Auth::id())->where("followed_id",$id)->first();
            if($cek){
                $cek->delete();
            }
        }
    }

    public function index(Request $req)
    {
    
        $skip = 0;
        $take = 10;

        if($req->page > 1){
            $skip = $take * $req->page-1;
        }





        $type = MetDa::distinct()
        ->where("study_program","!=",null)
        ->pluck("study_program");

        
        if($req->type){
            $filterType = "study_program = '".$req->type."'";
        }else{
            $filterType = "study_program != ''";
        }

        if($req->search){
            $filterSearch = "name like '%".$req->search."%'";
        }else{
            $filterSearch = "id != ''";
        }
    
         

        if($req->followed){
            $followed = user_follow::
                where("followed_id",$req->followed)
                ->pluck("user_id")->toArray();
            
            $metda = MetDa::
                whereRaw($filterType)
                ->whereRaw($filterSearch)
                ->whereIn("id",$followed)
                ->skip($skip)->take($take)
                ->orderBy("name","ASC")
                ->get();
        }
        else if($req->following){
            $following = user_follow::
            where("user_id",$req->following)
            ->pluck("followed_id")->toArray();

            $metda = MetDa::
                whereRaw($filterType)
                ->whereRaw($filterSearch)
                ->whereIn("id",$following)
                ->skip($skip)->take($take)
                ->orderBy("name","ASC")
                ->get();
        }
        else if($req->member_group){
            $following = group_follow::
                where("group_id",$req->member_group)
                ->pluck("user_id")->toArray();

            $metda = MetDa::
                whereRaw($filterType)
                ->whereRaw($filterSearch)
                ->whereIn("id",$following)
                ->skip($skip)->take($take)
                ->orderBy("name","ASC")
                ->get();
        }
        else{
            $metda = MetDa::
                whereRaw($filterType)
                ->whereRaw($filterSearch)
                ->skip($skip)->take($take)
                ->orderBy("name","ASC")
                ->get();
        }

      

        $res = $metda->map(function($u) {
                
                $follow = user_follow::
                    where("user_id",Auth::id())
                    ->where("followed_id",$u->id)
                    ->first();

                if($follow){
                    $u->followed = true;
                }

                return $u;
                
            });
        

        $respons = [
            "data" => $res,
            "type" => $type
        ];

        return response()->json($respons);
    }


}
