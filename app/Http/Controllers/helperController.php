<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class helperController extends Controller
{
    //
    public function listProdi(){
        $res = User::distinct("study_program")->where("study_program","!=","")->pluck("study_program");
        return $res;
    }
    public function listUniv(){
        $res = User::distinct("university")->where("university","!=","")->pluck("university");
        return $res;
    }
}
