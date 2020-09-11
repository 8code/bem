<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group as MetDa;
use Auth;
use App\group_follow;
use App\qna;
use App\User;
use App\qna_follow;



class groupController extends Controller
{



    
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
                        ->where("group_id",$id)
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
    public function follow($id){
        if(Auth::id()){
            
            $cek = group_follow::where("user_id",Auth::id())->where("group_id",$id)->first();
            if(!$cek){
                $follow = new group_follow;
                $follow->user_id = Auth::id();
                $follow->group_id = $id;
                $follow->save();
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



        $type = MetDa::distinct()->pluck("type");

        
        if($req->type){
            $filterType = "type = '".$req->type."'";
        }else{
            $filterType = "type != ''";
        }

        if($req->search){
            $filterSearch = "name like '%".$req->search."%'";
        }else{
            $filterSearch = "id != ''";
        }


        
    

        $metda = MetDa::
             whereRaw($filterType)
            ->whereRaw($filterSearch)
            ->skip($skip)->take($take)
            ->orderBy("last_active","DESC")
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
            "data" => $res,
            "type" => $type
        ];

        return response()->json($respons);
    }

    public function myGroup()
    {
        if(Auth::id()){
            $follow = group_follow::where("user_id",Auth::id())->pluck("group_id");
            $metda = MetDa::whereIn("id",$follow)
            ->orderBy("last_active","DESC")
            ->get();

            $res = $metda->map(function($g){
                $qna = qna::where("group_id",$g->id)->count();
                $gf = group_follow::where("group_id",$g->id)->count();
                $g->total_qna = $qna;
                $g->follow_total = $gf;
                return $g;
            });

            return response()->json($res);
        }else{
            return response()->json('');
        }
       
    }
    public function create(Request $request)
    {
        try {
            $metda = MetDa::create($request->all());
            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data'=> $th
            ]);
        }
       
        
    }

    public function show($username)
    {
        $metda = MetDa::where("username",$username)->first();
        if($metda){
            $cek = group_follow::where("user_id",Auth::id())->where("group_id",$metda->id)->first();

            if($cek){
                $metda->followed = true;
            }else{
                $metda->followed = false;
            }
        
            return response()->json($metda);
        }else{
            return "";
        }
    }

    public function edit(Request $request,$id)
    {
        try {
            MetDa::find($id)->update($request->all());
            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data'=> $th
            ]);
        }
       
  
    }

    public function delete($id)
    {
        //
         try {
            $metda = MetDa::find($id)->delete();
            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data'=> $th
            ]);
        }
    }
}
