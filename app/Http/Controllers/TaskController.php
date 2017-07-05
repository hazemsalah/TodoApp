<?php

namespace App\Http\Controllers;

use App\UserFollowingTasks;
use App\Notifications\updateTask;
use App\Task;

class TaskController extends Controller
{
    /**
     * Creating a task
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store()
    {

        $this->validate(request(), [

            'body' => 'required',
            'deadline' => 'required'

        ]);
        $task =Task::create([
                'body'=>request('body') ,
                'user_id'=>auth()->id(),
                'deadline' => request('deadline'),
                'private' => request('private'),
                'completed' => request('completed')
                ]);
        $task = $task->fresh();

        return response()->json("Task added");
    }


    /**
     * Toggling the complete status of a task.
     *
     * @request ('id') which is the task id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleCompleted()
    {
        $this->validate(request(), [
           'id' => 'required'
        ]);

        $task = Task::find(request()->input('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->completed = !$task->completed;
            $task->save();
            $watchingUser = UserFollowingTasks::where('task_id', $task->id)->get();
            foreach ($watchingUser as $user) {
                \Notification::send($task->user, new updateTask($user));
            }

            return response()->json("YAY completed EDITED");
        }
            return response()->json("Task Not yours");
    }


    /**
     * Toggling the private status of a task.
     *
     * @request ('id') which is the task id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePrivate()
    {
        $this->validate(request(), [
            'id' => 'required'
        ]);

        $task = Task::find(request()->input('id'));
        if (\Gate::allows('taskOwner', $task)) {
             $task->private = ! $task->private;
             $task->save();
            $watchingUser = UserFollowingTasks::where('task_id', $task->id)->get();
            foreach ($watchingUser as $user) {
                \Notification::send($task->user, new updateTask($user));
            }
            return response()->json("YAY private EDITED");
        } else {
            return response()->json("Task Not yours");
        }
    }

    /**
     * Updating the body of a task.
     *
     * @request ('body') the task body
     * @request ('id) the task id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBody()
    {

        $this->validate(request(), [
            'body' => 'required',
            'id' => 'required'
        ]);
        $task = Task::find(request('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->body = request('body');
            $task->save();
            $watchingUser = UserFollowingTasks::where('task_id', $task->id)->get();
            foreach ($watchingUser as $user) {
                \Notification::send($task->user, new updateTask($user));
            }
            return response()->json("body is updated");
        } else {
            return response()->json("This task is not yours");
        }
    }

    /**
     * Updating a task's deadline.
     *
     * @request('deadline') task deadline
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDeadline()
    {

        $this->validate(request(), [
            'deadline' => 'required'
        ]);
        $task = Task::find(request('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->deadline = request('deadline');
            $task->save();
            $watchingUser = UserFollowingTasks::where('task_id', $task->id)->get();
            foreach ($watchingUser as $user) {
                \Notification::send($task->user, new updateTask($user));
            }
            return response()->json("deadline is updated");
        } else {
            return response()->json("This task is not yours");
        }
    }


    /**
     * Deleting a task.
     *
     * @request ('id') the task id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTask()
    {

        $task = Task::find(request('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->delete();
            return response()->json("YAY task is deleted");
        } else {
            return response()->json("This task doesnt belong to you");
        }
    }

    /**
     * Getting a user's task (private & public) and all public tasks.
     *
     * @return \Illuminate\Http\JsonResponse array has
     * all the public tasks and private tasks of the logged in user
     */
    public function tasksUser()
    {
        $public_tasks = Task::where('private', 0)->get();
        $my_private_tasks = Task::where([
            ['private', 1],
            ['user_id', auth()->id()],
        ])->get();
        $followed_tasks = UserFollowingTasks::where([
            ['user_id', auth()->id()],
        ])->get();


        $result = $public_tasks->merge($my_private_tasks);
        $resultf = $result->add($followed_tasks);

        return response()->json($resultf);
    }

    /**
     * Getting all public tasks.
     *
     * @return \Illuminate\Http\JsonResponse array has all public tasks
     */
    public function tasksGuest()
    {
        $public_tasks = Task::where("private", 0)->get();
        return response()->json($public_tasks);
    }


    /**
     * Getting all the completed tasks.
     *
     * @return \Illuminate\Http\JsonResponse array has all completed tasks
     */
    public function getCompleted()
    {
        $tasks = Task::where([
            ['completed', 1],
            ['user_id', auth()->id()],
        ])->get();
        return response()->json($tasks);
    }

    /**
     * Get all the uncompleted tasks.
     *
     * @return \Illuminate\Http\JsonResponse array has all uncompleted tasks
     */
    public function getNotCompleted()
    {
        $tasks = $tasks = Task::where([
            ['completed', 0],
            ['user_id', auth()->id()],
        ])->get();
        return response()->json($tasks);
    }



}
