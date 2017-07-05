<?php

namespace App\Http\Controllers;

use App\Notifications\FollowTask;
use App\UserFollowingTasks;
use Illuminate\Http\Request;
use App\Task;
use App\User;

/**
 * Class UserFollowingTasksController
 * @package App\Http\Controllers
 */
class UserFollowingTasksController extends Controller
{
    /**
     * follows another user's task
     * @return \Illuminate\Http\JsonResponse
     */
    public function followTask(){
        if (auth()->id())
        {
        $this->validate(request(),[
            'task_id'=>'required'
        ]);

        $task=UserFollowingTasks::create([
            'user_id' =>auth()->id(),
            'task_id'=>request('task_id')
        ]);
        $user =User::find(auth()->id());

            \Notification::send($task->user, new FollowTask($user,$task));
        }
        else {
            $this->validate(request(),[
                'task_id'=>'required',
                'user_id'=>'required'
            ]);
            $task=UserFollowingTasks::create([
                'user_id' =>request('user_id'),
                'task_id'=>request('task_id')
            ]);
            $user =User::find(request('user_id'));
            \Notification::send($task->user, new FollowTask($user,$task));
        }
        return response()->json(['You Followed This Task']);
    }
}
