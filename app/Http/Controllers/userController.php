<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user_follow;
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
    
         
        $following = user_follow::
            where("user_id",Auth::id())
            ->get()->pluck("id");

        $res = MetDa::
             whereRaw($filterType)
            ->whereRaw($filterSearch)
            ->skip($skip)->take($take)
            ->orderBy("name","ASC")
            ->whereNotIn("id",$following)
            ->get();
        

        $respons = [
            "data" => $res,
            "type" => $type
        ];

        return response()->json($respons);
    }


}
