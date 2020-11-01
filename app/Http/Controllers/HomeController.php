<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\diamon;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function admin()
    {
        return view('admin');
    }

    public function topUpDiamon(Request $req){
        if(Auth::id() == 1){
            $tu = new diamon;
            $tu->user_id = null;
            $tu->ref = null;

            $user = User::where("username",$req->username)->first();
            if($user){
                $tu->to_id = $user->id;
                $tu->diamon = $req->diamon;
                $tu->save();

                return back()->withSuccess("Topup Berhasil");

            }else{
                return back()->withError("Username tidak ditemukan");
            }

        }
    }
}
