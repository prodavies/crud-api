<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
    //creating function for registering and login users
    public function register(Request $request){
        //validate inputs
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);

        //create new user
        $user = new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=\Hash::make($request->password);
        $user->save();
       
        //creating token for the new created user and give user access to that token
        $token = $user->createToken('authUserTokenForWeb')->accessToken;
        return response()->json(['token'=>$token],200);
    }

    //function for login user to api
    public function login(Request $request){
        //user login credential
        $login_credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
            //check if the attempt to authenticate with given credentials succeed
        if(auth()->attempt($login_credentials)){
            $token = auth()->user()->createToken('authUserTokenForWeb')->accessToken;
            return response()->json(['token'=>$token],200);
        }
        else{
            return response()->json(['error'=>'Sorry! your not authorized, try again']);
        }
    }

    //if interested on authenticated details
    public function auDetail(){
        return response()->json(['user'=>auth()->user()],200);
    }
}
