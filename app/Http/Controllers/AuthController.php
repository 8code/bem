<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;

class AuthController extends Controller
{
    //
    public function redirectToFb(Request $req)
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFbCallback()
    {
        $user = Socialite::driver('facebook')->user();

        // dd($user);
        
        $authUser = User::where('fb_id', $user->id)->first();
        if ($authUser) {
            return redirect('register')->with("info","Akun Facebook Telah terhubung dengan akun @".$authUser->username);
        }
        else{
            return redirect('register')->with("data",$user);
        }
      
    }
}
