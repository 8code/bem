<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return Auth::user();
    }

    public function ubahPass(Request $req)
    {
        $user = User::find(Auth::id());
        $user->password = Hash::make($req->password);
        $user->update();
        if($user){
            return "berhasil";
        }else{
            return "gagal";
        }
    }
}
