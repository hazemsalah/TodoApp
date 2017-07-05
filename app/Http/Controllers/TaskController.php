<?php

namespace App\Http\Controllers;

use App\Notifications\UpdateTask;
use Illuminate\Http\Request;
use App\Notifications\reminder;
use App\User;
use App\Task;
use App\UserFollowingTasks;

/**
 * Class TaskController
 * @package App\Http\Controllers
 */
class TaskController extends Controller
{

    /**
     * TaskController constructor.
     */
    public function __construct()
    {

    }

    /**
     * return all the tasks that a user follows, public tasks and his tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskUser()
    {
         $publicTasks = Task::where('private', 0)->get();

         $userTasks = Task::where('user_id', auth()->id())->get();

         $followedTasks = UserFollowingTasks::where('user_id', auth()->id())->get();

         $allTasks = $publicTasks->merge($userTasks);

         $result = $allTasks->merge($followedTasks);

         return response()->json($result);

}

    /**
     * return all the public tasks to the guest
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskGuest()
    {

        $tasks = Task::where('private', 0)->get();


        return response()->json($tasks);

    }

    /**
     * @request('body')
     * @request('deadline')
     * create a new task
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
{

        $this->validate(request(), [

        'body' => 'required',

            'deadline' => ' required'

        ]);

        $task = Task::create([

         'body' => request('body'),

         'user_id' => auth()->id(),

         'deadline' => request('deadline')


          ]);

           $task = $task->fresh();

           \Notification::send($task->user,  new reminder($task));

           return response()->json(['result' => $task]);

    }

    /**
     * @request('task_id')
     * update a specific task with all the requested fields
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {

          $this->validate(request(), [

                'id' => 'required',

            ]);

           $id = request('id');

           $task = Task::findOrFail($id);


          if (\Gate::denies('taskOwner', $task)) {
              return response()->json("This Task doesn't belong to you");
          }

            $input = request()->all();

            $task->fill($input)->save();

            $userId=$task->user_id;

            $user = User::find($userId);


          \Notification::send($task->user, new UpdateTask($task, $user));

            return response()->json("Task successfully updated :)");
    }


    /**
     * @request('task_id')
     * delete a specific task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {

        $id = request('id');

        $task = Task::findOrFail($id);

        if (\Gate::denies('taskOwner', $task)) {
               return response()->json("You cannot delete this task");
        }

        $task->delete();

        return response()->json('Task successfully deleted!');
    }

    /**
     * return the completed tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompleted()
    {

        $completedTasks = Task::where([['completed', 1],['user_id',auth()->id()]])->get();

        return response()->json($completedTasks);

    }

    /**
     * return the uncompleted Tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotCompleted()
    {

        $notCompletedTasks = Task::where([['completed', 0],['user_id',auth()->id()]])->get();

        return response()->json($notCompletedTasks);

    }

}
