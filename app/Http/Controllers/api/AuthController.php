<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use DB;
use Auth;
use App\Events\LoginandOutEvent;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password')
        );

        if(!Auth::attempt($user_data))
        {
            return response(['error' => true, 'data' => 'Incorrect Username or Password']);
        }

        $accessToken = $request->user()->createToken('authToken');
        
        $token = $accessToken->token;

        if ($request->remember_me) {
            $token->expires_at = \Carbon\Carbon::now()->addWeeks(1); //1  week expiration
        }

        $token->save(); //eloquent
      //  $user = User::with('UserRoles')->where('id', Auth::user()->id)->first();
        $user = User::where('id', Auth::user()->id)
        ->update(['is_online' => 1]);

        return response(['error' => false, 'data' => Auth::user()->id, 'access_token' => $accessToken->accessToken]);
        //return $user->createToken('Auth Token')->accessToken;
        event(new LoginandOutEvent($user));
    }

    public function UserData(Request $request){
     //   return User::with('UserRoles')->where('id', Auth::user()->id)->first();
     //  return $request->user()->with('UserRoles')->where('id', Auth::user()->id)->first();
        return $request->user();
    }
    
    
}
