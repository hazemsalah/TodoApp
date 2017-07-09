<?php
namespace App\Http\Controllers;
use App\Mail\TaskInvitation;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use App\Mail\Welcome;
use JWTAuthException;
use Auth;
use Socialite;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var User
     */
    private $user;
    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user){
        $this->user = $user;
    }
    /**
     * registers a new user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        \Mail::to($user)->send(new Welcome);
       return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }
    /**
     * logs in with a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect('/');
    }
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }
    /**
     * gets the logged in user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
    /**
     * deletes a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json('You are successfully logged out');
    }
    /**
     * changes the user's password
     * @request('new_password')
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(){
        $user=User::find(auth()->id());
        $user->password=bcrypt(request('new_password'));
        $user->save();
        return response()->json('Password Updated Successfully');
    }
    /**
     * invites another user to view private task
     * @request('task_id')
     * @request('user_id')
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite(){
        $this->validate(request(),[
            'task_id' => 'required',
            'user_id'=> 'required'
        ]);
        $task=Task::find(request('task_id'));

        $user=User::find(request('user_id'));
        if (\Gate::denies('taskOwner', $task)) {
            return response()->json("Task doesn't Belong To you");
        }
        \Mail::to($user)->send(new TaskInvitation($user,$task));
        return response()->json("Invitation Mail Sent Successfully");
    }
    /**
     * searches a certain user by name
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUser(){
        $this->validate(request(),[
            'name' => 'required'
        ]);
        $users =User::where('name',request('name'))->orWhere('name', 'like', '%' . request('name') . '%')->get();
        return response()->json($users);
    }
}