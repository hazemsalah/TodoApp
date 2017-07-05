<?php
namespace App\Http\Controllers;

use App\Mail\TaskInvitation;
use Illuminate\Http\Request;
use App\Http\Requests;
use JWTAuth;
use App\User;
use App\Task;
use JWTAuthException;
use App\Mail\RegistrationMail;

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
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Registering a user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email'=> 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);
        \Mail::to($user)->send(new RegistrationMail);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }


    /**
     * Log a user in.
     *
     * @param Request $request taking 'email' & 'password'
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
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

    /**
     * Gets the authenticated user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser(Request $request)
    {
        $user = \Auth::user();
        return response()->json(['result' => $user]);
    }

    /**
     * Log a user out.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return redirect('/');
    }

    /**
     * Updating a user's password.
     *
     * @request ('password')
     *
     * @return string
     */
    public function updatePassword()
    {
        $user = User::find(auth()->id());
        $user->password = bcrypt(request('password'));
        $user->save();
        return ("password is Updated");
    }

    /**
     * Searching for a user by name.
     *
     * @request ('name')
     *
     * @return \Illuminate\Http\JsonResponse array of users with the requested name
     */
    public function searchUser()
    {
        $name = request('name');
        $users = User::where('name', $name)->orWhere('name', 'like', '%' . request('name') . '%')->get();
        return response()->json($users);
    }

    /**
     * Invite a user to follow a private task.
     *
     * @request ('task_id)
     * @request ('user_id')
     *
     * @return \Illuminate\Http\JsonResponse if user is not an owner of the task
     * @return send mail to the invited user if an owner invites him for a task
     */
    public function invite()
    {
        $this->validate(request(), [
            'task_id' => 'required',
            'user_id' => 'required'
        ]);
        $task = Task::find(request('task_id'));
        $user = User::find(request('user_id'));
        if (\Gate::denies('taskOwner', $task)) {
            return response()->json("you are not an owner of this task");


        }
        \Mail::to($user)->send(new TaskInvitation($user, $task));
    }
}