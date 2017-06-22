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
        $this->middleware('jwt.auth')->except(['login', 'register', 'tasksGuest']);
    }

    //
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
//        return redirect('/');

        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_email_or_password'], 401);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthUser(Request $request){
        $user = \Auth::user();
        return response()->json(['result' => $user]);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return redirect('/');
    }

    public function updatePassword(){
        $user = User::find(auth()->id());
        $user->password = bcrypt(request('password'));
        $user->save();
        return ("password is Updated");

    }
}