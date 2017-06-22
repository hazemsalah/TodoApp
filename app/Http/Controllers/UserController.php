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
    public function __construct(User $user){
        $this->user = $user;
        $this->middleware('jwt.auth')->except(['login','register']);
    }

    public function register(Request $request){
        $this->validate(request(),[
            'name' => 'required',
            'email'=> 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

       return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_email_or_password'], 422);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }


        return response()->json(compact('token'));
    }
    public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
    public function destroy(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json('You are successfully logged out');
    }
    public function changePassword(){
        $user=User::find(auth()->id());
        $user->password=bcrypt(request('new_password'));
        $user->save();
        return response()->json('Password Updated Successfully');
    }
}