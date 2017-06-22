<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use JWTAuth;

use App\User;

use JWTAuthException;

class UserController extends Controller
{
    private $user;

    public function __construct(){

        $this->middleware('jwt.auth')->except(['login','register']);

    }

    public function register(Request $request){

      $this->validate(request(),[
            'name' => 'required',
            'email'=> 'required|email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([

          'name' => $request->get('name'),

          'email' => $request->get('email'),

          'password' => bcrypt($request->get('password'))

        ]);
         return redirect('/');

        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        $token = null;

        try {

           if (!$token = JWTAuth::attempt($credentials)) {

            return response()->json(['invalid_email_or_password'], 422);

           }
        }
        catch (JWTAuthException $e) {

            return response()->json(['failed_to_create_token'], 500);
        }


          return response()->json(compact('token'));

           //return redirect('/tasks');
    }

    public function getAuthUser(Request $request){

        $user = \Auth::user();

        return response()->json(['result' => $user]);
    }

    public function destroy(){

       JWTAuth::invalidate(JWTAuth::getToken());

       session()->flash('message','You are logged out :( ');

        return redirect('/');
    }
    public function updatePassword(){

           $user = User::find(auth()->id());
         $user->password = bcrypt(request('password'));
                $user->save();

              return('Your password has changed successfully ');
        //session()->flash('message','Your password has changed successfully ');
    }

    public function search(){

        $requestedUser = request('userEmail');

    }

}
