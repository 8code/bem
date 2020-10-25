<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\report;
use Auth;

class reportController extends Controller
{
    //
    public function store($id,$text){
        if(Auth::id()){

            $r = new report;
            $r->user_id = Auth::id();
            $r->quest_id = $id;
            $r->text = $text;
            $r->save();

            return "success";
        }
    }
}
