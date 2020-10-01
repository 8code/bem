<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\activity;
use App\group;
use App\User;
use DB;
use Illuminate\Support\Carbon;
use App\user_follow;
use Auth;

class populerController extends Controller
{
    //
    public function tagar(){

        // return "ss";
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');

        $date = $year."-".$month."-".$day;

        
        $tagar = activity::
         select('tagar', DB::raw('count(*) as total'))
        ->groupBy('tagar')
        ->orderBy("total","DESC")
        ->where("tagar","!=",null)
        ->where('created_at',">=",$date)
        ->get();

        if( count($tagar) == 0){
            if($month == 1){
                $year = $year-1;
                $month = 12;
            }else{
                $month = $month-1;
            }
            
            $date = $year."-".$month."-".$day;

            $tagar = activity::
            select('tagar', DB::raw('count(*) as total'))
            ->groupBy('tagar')
            ->orderBy("total","DESC")
            ->where("tagar","!=",null)
            ->where('created_at',">=",$date)
            ->get();
        }

        if(count($tagar) == 0){
            $date = ($year-1)."-".$month."-".$day;
            $tagar = activity::
            select('tagar', DB::raw('count(*) as total'))
           ->groupBy('tagar')
           ->orderBy("total","DESC")
           ->where("tagar","!=",null)
           ->where('created_at',">=",$date)
           ->get();
        }

        
        return $tagar->take(5);
    }


    public function group(){
        return group::orderBy("last_active","DESC")->get()->take(5);
    }


    public function user(){

        // return "ss";
        $day = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->addMonth(1)->format('Y-m-d');
        $year = Carbon::now()->format('Y-m-d');

        
        $user = activity::
         select('user_id', DB::raw('count(user_id) as total'))
        ->groupBy('user_id')
        ->orderBy("total","DESC")
        ->where("user_id","!=",null)
        ->where('created_at',">=",$day)
        ->get();

        if(count($user) < 5){
            $user = activity::
            select('user_id', DB::raw('count(user_id) as total'))
            ->groupBy('user_id')
            ->orderBy("total","DESC")
            ->where("user_id","!=",null)
            ->where('created_at',">=",$month)
            ->get();
        }

        if(count($user) < 5 ){
            $user = activity::
            select('user_id', DB::raw('count(user_id) as total'))
           ->groupBy('user_id')
           ->orderBy("total","DESC")
           ->where("user_id","!=",null)
           ->where('created_at',">=",$year)
           ->get();
        }

        
        return $user->take(5)->map(function($u) {
            $u->user = User::find($u->user_id);
            
            $follow = user_follow::
                where("user_id",Auth::id())
                ->where("followed_id",$u->user_id)
                ->first();

            if($follow){
                $u->user->followed = true;
            }
            return $u;
        });
    }
}
