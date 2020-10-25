<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\event as MetDa;
use App\qna;
use App\qna_follow;
use App\join_event;
use Auth;


class eventController extends Controller
{


    public function byRoomBy($id){
        $metda = MetDa::
        join("channels","events.id","channels.event_id")
        ->where("channels.room_id",$id)
        ->select("events.*")
        ->first();
        return $metda;
    }

    public function myEvent()
    {
        if(Auth::id()){
            $join = join_event::where("user_id",Auth::id())->pluck("event_id")->toArray();

            // Where Tanggal Terdekat
            $metda = MetDa::
             whereIn("id",$join)
            ->with("channel")
            ->latest()
            ->paginate(5);

            return response($metda);
        }
    }

    public function joinEvent($id){
        if(Auth::id()){
            $cek = join_event::where("user_id",Auth::id())->where("event_id",$id)->first();
            if(!$cek){
                $joinEvent = new join_event;
                $joinEvent->user_id = Auth::id();
                $joinEvent->event_id = $id;
                $joinEvent->status = 0;
                $joinEvent->price = 0;
                $joinEvent->message = 0;
                $joinEvent->save();
                return "success";
            }else{
                return "success";
            }
          
        }
    }

    public function event_explore(Request $req){
        if(Auth::id()){
                
            $skip = 0;
            $take = 9;

            if($req->page > 1){
                $skip = $take * $req->page-1;
            }

            if($req->search){
                $filterSearch = "text like '%".$req->search."%'";
            }else{
                $filterSearch = "id != ''";
            }
    
            
            $metda = qna::whereRaw($filterSearch)
                ->with("group")
                ->with("user")
                ->with("quest")
                ->where("type",2)
                ->where("status",1)
                ->where("quest_id",null)
                ->orderBy("activity","DESC")
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
                
            });

            $res = collect($metda)->skip($skip)->take($take)->toArray();

            $respons = [
                "data" => $res,
                "total" => count($res)
            ];

            return response()->json($respons);
}
    }
    public function questEvents()
    {
        $data =  qna::where("type",2)
        ->with("group")
        ->with("user")
        ->with("quest")
        ->where("status",1)
        ->where("quest_id",null)
        ->orderBy("activity","DESC")
        ->orderBy("id","DESC")
        ->where("status",1)->get()->take(10);
        return $data;
    }

    public function index()
    {
        $metda = MetDa::latest()->paginate(5);
        return response($metda);
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
