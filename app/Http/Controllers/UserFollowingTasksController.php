<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\UserFollowingTasks;
use App\Notifications\Followers;

/**
 * Class UserFollowingTasksController
 * @package App\Http\Controllers
 */
class UserFollowingTasksController extends Controller
{
    /**
     *follow a user's public task without an invitation
     *and the owner user gets a notification when someone follow any of his tasks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function followTasks()
    {
        if (auth()->id()) {
            $this->validate(request(), [

                'task_id' => 'required',

            ]);
            UserFollowingTasks::create([

                'task_id' => request('task_id'),

                'user_id' => auth()->id()
            ]);
            $user = User::find(request('user_id'));

            $task=Task::find(request('task_id'));

            \Notification::send($task->user, new Followers($user, $task));

            return response()->json("You have successfully followed this task :)");
        } else {

            UserFollowingTasks::create([

                'task_id' => request('task_id'),

                'user_id' => request('user_id')
            ]);

            $user = User::find(request('user_id'));

            $task=Task::find(request('task_id'));

            \Notification::send($task->user, new Followers($user, $task));

            return response()->json("You have successfully followed this task :)");
        }
    }

}
