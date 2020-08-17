<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group as MetDa;
use Auth;


class groupController extends Controller
{

    public function index()
    {
        $metda = MetDa::latest()->paginate(5);
        return response()->json($metda);
    }

    public function myGroup()
    {
        if(Auth::id()){
            $metda = MetDa::where("user_id",Auth::id())->orderBy("id","DESC")->get();
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
