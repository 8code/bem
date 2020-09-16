<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\activity;
use App\group;
use App\User;
use DB;
use Illuminate\Support\Carbon;


class populerController extends Controller
{
    //
    public function tagar(){

        // return "ss";
        $day = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->addMonth(1)->format('Y-m-d');
        $year = Carbon::now()->format('Y-m-d');

        
        $tagar = activity::
         select('tagar', DB::raw('count(*) as total'))
        ->groupBy('tagar')
        ->orderBy("total","DESC")
        ->where("tagar","!=",null)
        ->where('created_at',">=",$day)
        ->get();

        if(!$tagar){
            $tagar = activity::
                select('tagar', DB::raw('count(*) as total'))
            ->groupBy('tagar')
            ->orderBy("total","DESC")
            ->where("tagar","!=",null)
            ->where('created_at',">=",$month)
            ->get();
        }

        if(!$tagar){
            $tagar = activity::
            select('tagar', DB::raw('count(*) as total'))
           ->groupBy('tagar')
           ->orderBy("total","DESC")
           ->where("tagar","!=",null)
           ->where('created_at',">=",$year)
           ->get();
        }

        
        return $tagar->take(3);
    }


    public function group(){
        return group::orderBy("last_active","DESC")->get()->take(3);
    }


    public function user(){

        // return "ss";
        $day = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->addMonth(1)->format('Y-m-d');
        $year = Carbon::now()->format('Y-m-d');

        
        $user = activity::
         select('user_id', DB::raw('count(*) as total'))
        ->groupBy('user_id')
        ->orderBy("total","DESC")
        ->where("user_id","!=",null)
        ->where('created_at',">=",$day)
        ->get();

        if(!$user){
            $user = activity::
            select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy("total","DESC")
            ->where("user_id","!=",null)
            ->where('created_at',">=",$month)
            ->get();
        }

        if(!$user){
            $user = activity::
            select('user_id', DB::raw('count(*) as total'))
           ->groupBy('user_id')
           ->orderBy("user","DESC")
           ->where("user_id","!=",null)
           ->where('created_at',">=",$year)
           ->get();
        }

        
        return $user->take(3)->map(function($u) {
            $u->user = User::find($u->user_id);
            return $u;
        });
    }
}
