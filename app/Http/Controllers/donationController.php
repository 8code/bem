<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\donation as MetDa;
use App\qna;
use App\activity;


class donationController extends Controller
{


    public function questDonations()
    {
        $data =  qna::where("type",3)
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
