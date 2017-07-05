<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Notifications\followTask;
use App\UserFollowingTasks;
use Illuminate\Http\Request;

class UserFollowingTasksController extends Controller
{
    /**
     * Following a private or a public task posted by another user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function followTask()
    {
        $this->validate(request(), [
            'task_id' => 'required'
        ]);
        if (auth()->id()) {
            UserFollowingTasks::create([
                'user_id' => auth()->id(),
                'task_id' => request('task_id')

            ]);
            $task = Task::find(request('task_id'));
            $user = User::find(auth()->id());
            \Notification::send($task->user, new followTask($task, $user));
            return response()->json("You followed this task");
        } else {
            UserFollowingTasks::create([
                'user_id' => request('user_id'),
                'task_id' => request('task_id')

            ]);

            $task = Task::find(request('task_id'));
            $user = User::find(request('user_id'));
            \Notification::send($task->user, new followTask($task, $user));
            return response()->json("You followed this task");
        }
    }
}
