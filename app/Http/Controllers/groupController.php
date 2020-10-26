<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group as MetDa;
use Auth;
use App\group_follow;
use App\qna;
use App\User;
use App\qna_follow;
use App\activity;
use Storage;


class groupController extends Controller
{

    public function quest(Request $req, $id){
        // return $id;
        if(Auth::id()){
                
                    $skip = 0;
                    $take = 5;

                    if($req->page > 1){
                        $skip = $take * ($req->page-1);
                    }

               
                    $group = Metda::find($id);

                    if($req->filter){
                        if($req->filter == 'New'){
                            $filterType = " quest_id is null";
                        }
                        else if($req->filter == 'Event'){
                            $filterType = 'type = 2';
                        } else if($req->filter == 'Voice'){
                            $filterType = 'audio != null';
                        }
                        else{
                            $filterType = 'id';
                        }
                    }else{
                        $filterType = 'id ';
                    }

                    
                    $metda = qna::orderBy("id","DESC")
                        ->whereRaw($filterType)
                        ->where("group_id",$id)
                        ->where("status",1)
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

                        $dataAct = [
                            "user_id" => Auth::id(),
                            "quest_id" => $q->id,
                            "tipe" => 0
                        ];
                        $cekView = activity::where("user_id",Auth::id())
                        ->where("quest_id",$q->id)
                        ->where("tipe",0)
                        ->first();

                        if(!$cekView){
                            activity::create($dataAct);
                                // Update View
                            $q->view = activity::where("quest_id",$q->id)->where("tipe",0)->count()
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
    public function unfollow($id){
        if(Auth::id()){
            
            $cek = group_follow::where("user_id",Auth::id())->where("group_id",$id)->first();
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
            "total" => count($res),
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

            $id = MetDa::insertGetId($request->all());

            
            $follow = new group_follow;
            $follow->user_id = Auth::id();
            $follow->group_id = $id;
            $follow->save();
               

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
        $metda = MetDa::where("username",$username)
        ->with("owner")->first();
        if($metda){
            $cek = group_follow::where("user_id",Auth::id())->where("group_id",$metda->id)->first();
            $totalFollower = group_follow::where("group_id",$metda->id)->count();

            $metda->follower = $totalFollower;

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

    public function edit(Request $req,$id)
    {
        
        try {

            $data = Metda::find($id);

            if($data->user_id == Auth::id()){
               

            if($data->username != $req->username){
                $cekUsername = User::where("username",$req->username)->first();
                if(!$cekUsername){
                    $data->username =$req->username;
                }else{
                    $res = [
                        "info" => "Username Telah digunakan"
                    ];
                    return $res;
                }
            }

            if($req->avatar){
                Storage::delete($data->id);
        
                $image_64 = $req->avatar; //your base64 encoded data
        
                $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        
                // find substring fro replace here eg: data:image/png;base64,
        
                $image = str_replace($replace, '', $image_64); 
        
                $image = str_replace(' ', '+', $image); 
        
                $imageName = $data->id.'-group.png';
                
        
                Storage::disk('public')->put($imageName, base64_decode($image));

                $data->avatar = env("APP_URL")."/storage/".$imageName;
            }

            $data->name = $req->name;
            $data->instagram = $req->instagram;
            $data->type = $req->type;
            $data->desc = $req->desc;

            if($req->admin){
                $admin = User::where("username",$req->admin)->first();
                if($admin){
                    $data->user_id = $admin->id;
                }
            }
            $data->save();

                $res = [
                    "status" => "Success"
                ];
                
                return $res;

                
            }else{
                return "";
            }
            
         



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
