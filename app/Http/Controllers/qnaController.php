<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\qna as MetDa;
use App\qna_follow;
use Auth;
use App\group_follow;
use App\qna;
use App\User;


class qnaController extends Controller
{

    public function quest_home(Request $req){
        if(Auth::id()){
                
                    $skip = 0;
                    $take = 5;

                    if($req->page > 1){
                        $skip = $take * $req->page-1;
                    }

               

                    if($req->search){
                        $filterSearch = "text like '%".$req->search."%'";
                    }else{
                        $filterSearch = "id != ''";
                    }
            
                    
                    $metda = qna::orderBy("id","DESC")
                        ->whereRaw($filterSearch)
                        ->with("group")
                        ->with("user")
                        ->with("quest")
                        ->skip($skip)->take($take)
                        ->get();



                    $metda->map(function($q) {

                        if($q->quest){

                            $q->membalas_user = User::find($q->quest->user_id)->username;
                        }

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
            
            $cek = qna_follow::where("user_id",Auth::id())->where("quest_id",$id)->first();
            if(!$cek){
                $follow = new qna_follow;
                $follow->user_id = Auth::id();
                $follow->quest_id = $id;
                $follow->save();
            }
        }
    }
    public function index()
    {
        $metda = MetDa::latest()->paginate(5);
        return response($metda);
    }
    public function create(Request $req)
    {
      
        try {
            if(Auth::id()){

                $metda = new MetDa;
                $metda->text = $req->text;
                $metda->audio = $req->audio;
                $metda->quest_id = $req->quest_id;
                $metda->group_id = $req->group_id;
                $metda->user_id = Auth::id();
                $metda->save();

                return response()->json([
                    'success' => true
                ]);
             }
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'data'=> $th
            ]);
        }
       
        
    }

    public function show($id)
    {
        $metda = MetDa::find($id);
        return response($metda);
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
