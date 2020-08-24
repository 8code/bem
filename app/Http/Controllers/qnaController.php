<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\qna as MetDa;
use Auth;


class qnaController extends Controller
{

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
