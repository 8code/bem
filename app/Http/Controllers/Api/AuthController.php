<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Str;
use Storage;


class AuthController extends Controller
{



    public function loginfb(Request $request)
    {
        
        $res = Http::get('https://graph.facebook.com/'.$request->userID.'?fields=id,name,email&access_token='.$request->accessToken);

        $cek = json_decode($res->getBody()); 
        
        if(!isset($cek->id)){
            return response()->json(['message' => 'Invalid Credentials']);
        }

        $user = User::where("fb_id",$request->userID)->first();
        if($user){
            if(Auth::loginUsingId($user->id)){
                // Success
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response()->json(['user' => auth()->user(), 'access_token' => $accessToken]);
            }
        }else{
            // Register User

            $user = new User;
            $user->fb_id = $cek->id;
            $user->username = $cek->id;
            $user->name = $cek->name;
            $user->avatar = 'https://graph.facebook.com/'.$cek->id.'/picture';
            $user->password = Hash::make("xpas-gen".time());
            $user->email = $cek->email;
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->save();


            if(Auth::loginUsingId($user->id)){
                // Success
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response()->json(['user' => auth()->user(), 'access_token' => $accessToken]);
            }

            
        }
      


  

    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        
        if(filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            //user sent their email 
            Auth::attempt(['email' => $request->username, 'password' => $request->password]);
        } else {
            //they sent their username instead 
            Auth::attempt(['username' => $request->username, 'password' => $request->password]);
        }

        if (!Auth::check() ) {
            return response()->json(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(['user' => auth()->user(), 'access_token' => $accessToken]);

    }
    public function uploadImage(Request $req){
        if(Auth::id()){
            $image_64 = $req->image; 
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
          
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
          
           $image = str_replace($replace, '', $image_64); 
          
           $image = str_replace(' ', '+', $image); 
          
           $imageName = Auth::id()."/".Str::random(10).'.'.$extension;
          
           Storage::disk('public')->put($imageName, base64_decode($image));
    
           return response()->json(['url' => $imageName]);
        }else{
           return response()->json(['url' => '']);
        }

    }
}