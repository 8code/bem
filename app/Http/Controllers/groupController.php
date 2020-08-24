<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group as MetDa;
use Auth;
use App\group_follow;
use App\qna;
use App\qna_follow;



class groupController extends Controller
{



    
    public function quest(Request $req, $id){
        if(Auth::id()){
                
                    $skip = 0;
                    $take = 20;

                    if($req->page > 1){
                        $skip = $take * $req->page-1;
                    }

               

                    if($req->filter){
                        if($req->filter == 'Quest Only'){
                            $filterType = " quest_id is null";
                        }else if($req->filter == 'Quest & Balasan'){
                            $filterType = 'id';
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



                    $metda->map(function($q) {

                        $q->qna_total = qna::where("quest_id",$q->id)->count();

                        $q->total_follower = qna_follow::where("quest_id",$q->id)->count();


                        $follow = qna_follow::
                            where("user_id",Auth::id())
                            ->where("quest_id",$q->id)
                            ->first();

                        if($follow){
                            $q->followed = true;
                        }
                        
                    });

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


        $metda = MetDa::orderBy("name","ASC")
            ->whereRaw($filterType)
            ->whereRaw($filterSearch)
            ->skip($skip)->take($take)
            ->get();


        $metda->map(function($g) {

            $qna = qna::where("group_id",$g->id)->count();
            $g->qna_total = $qna;
            
            $follow = group_follow::
                where("user_id",Auth::id())
                ->where("group_id",$g->id)
                ->first();
        
            if($follow){
                $g->followed = true;
            }
            
        });

        $respons = [
            "data" => $metda,
            "type" => $type
        ];

        return response()->json($respons);
    }

    public function myGroup()
    {
        if(Auth::id()){
            $follow = group_follow::where("user_id",Auth::id())->pluck("group_id");
            $metda = MetDa::whereIn("id",$follow)
            ->orderBy("name","ASC")
            ->get();

            $metda->map(function($g){
                $qna = qna::where("group_id",$g->id)->count();
                $g->qna_total = $qna;
            });

            return response()->json($metda);
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
