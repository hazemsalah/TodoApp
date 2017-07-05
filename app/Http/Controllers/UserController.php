<?php

namespace App\Http\Controllers;

use App\Mail\TaskInvitation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use App\Mail\Welcome;
use App\Task;
use JWTAuthException;

class UserController extends Controller
{
    /**
     *$var user
     */
    private $user;

    /**
     * UserController constructor.
     */
    public function __construct()
    {

        $this->middleware('jwt.auth')->except(['login','register']);

    }

    /**
     * @param Request $request
     * new user registers to the system
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email'=> 'required|email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([

          'name' => $request->get('name'),

          'email' => $request->get('email'),

          'password' => bcrypt($request->get('password'))

        ]);

          \Mail::to($user)->send(new Welcome);

        return response()->json(['User created successfully']);
    }

    /**
     * @param Request $request
     * user logs in to the system
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

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

    /**
     * get the authenticated user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser()
    {

        $user = \Auth::user();

        return response()->json(['result' => $user]);
    }

    /**
     * user logs out from the system
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {

       JWTAuth::invalidate(JWTAuth::getToken());

        session()->flash('message', 'You are logged out :( ');

        return redirect('/');
    }

    /**
     * Updates the password of the authenticated user.
     *
     * @return string
     */
    public function updatePassword()
    {

           $user = User::find(auth()->id());
           $user->password = bcrypt(request('password'));
           $user->save();

              return('Your password has changed successfully ');
    }

    /**
     * searches for another user by name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {

        $this->validate(request(), [
            'name' => 'required'
        ]);

        $requestedUser = request('name');

        $users = User::where('name', $requestedUser)->orWhere('name', 'like', '%' . request('name') . '%')->get();

        return response()->json($users);

    }

    /**
     *invite another user to follow my private tasks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite()
    {
        $this->validate(request(), [
            'task_id' => 'required',
            'user_id' => 'required'
        ]);

        $task= Task::find(request('task_id'));
        $invitedUser = User::find(request('user_id'));

        if (\Gate::denies('taskOwner', $task)) {
            return response()->json("This Task doesn't belong to you");
        }
        \Mail::to($invitedUser)->send(new TaskInvitation($invitedUser, $task));

        return response()->json("Your Email has been sent");
    }

}
