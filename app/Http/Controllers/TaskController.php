<?php

namespace App\Http\Controllers;

use App\Events\SendReminders;
use App\Notifications\reminder;
use App\Notifications\UpdateTask;
use App\UserFollowingTasks;
use Illuminate\Http\Request;
use App\Task;

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
     * creates a task
     * @request('body') required
     * @request('deadline') required
     * @request('user_id')
     * @request('task_id')
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (){
        $this->validate(request(),[
            'body'=>'required',
            'deadline'=>'required'
        ]);
        $task=Task::create([
            'user_id' =>auth()->id(),
            'body'=>request('body'),
            'deadline'=>request('deadline')
        ]);
        $task = $task->fresh();
        return response()->json(['result' => $task]);
    }
    /**
     * toggles the completion of task
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleComplete(){
        $this->validate(request(),[
            'task_id'=>'required'
        ]);
        $task=Task::find(request('task_id'));
        if (\Gate::denies('taskOwner', $task)) {
            return response()->json("Task doesn't Belong To you");
        }
        $task->completed=!$task->completed;
        $task->save();
        $users = UserFollowingTasks::where('task_id',$task->id)->get();
        foreach($users as $user){
            \Notification::send($task->user, new UpdateTask( $user));
        }
        return response()->json(['result' => $task]);
    }
    /**
     * toggles whether a task is private or not
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePrivate(){
        $this->validate(request(),[
            'task_id'=>'required'
        ]);
        $task=Task::find(request('task_id'));
        if(\Gate::denies('taskOwner', $task)){
            return response()->json("Task doesn't Belong To you");
        }
        $task->private=!$task->private;
        $task->save();
        $usersFollowingTask = UserFollowingTasks::where('task_id',$task->id)->get();
        foreach($usersFollowingTask as $userFollowingTask){

            \Notification::send($task->user, new UpdateTask( $userFollowingTask));
        }
        return response()->json(['result' => $task]);
    }
    /**
     * updates the body of the task
     * @request('body') required
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTask(){
        $this->validate(request(),[
            'task_id'=>'required',
            'body'=>'required'
        ]);
        $task=Task::find(request('task_id'));
        if(\Gate::denies('taskOwner', $task)){
            return response()->json("Task doesn't Belong To you");
        }
        $task->body=request('body');
        $task->save();
        $usersFollowingTask = UserFollowingTasks::where('task_id',$task->id)->get();
        foreach($usersFollowingTask as $userFollowingTask){
            \Notification::send($task->user, new UpdateTask( $userFollowingTask));
        }
        return response()->json(['result' => $task]);
    }

    /**
     * updates the deadline of a task
     * @request('deadline') required
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDeadline(){
        $this->validate(request(),[
            'task_id'=>'required',
            'deadline'=>'required'
        ]);
        $task = Task::find(request('task_id'));
        if(\Gate::denies('taskOwner', $task)){
            return response()->json("Task doesn't Belong To you");
        }
         $task->deadline=request('deadline');
            $task->save();
        $usersFollowingTask = UserFollowingTasks::where('task_id',$task->id)->get();
        foreach($usersFollowingTask as $userFollowingTask){
            \Notification::send($task->user, new UpdateTask( $userFollowingTask));
        }
            return response()->json(['result' => $task]);
    }
    /**
     * shows the tasks to the guest
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTasksGuest(){
        $tasks =Task::where('private',0)->get();
        return response()->json( $tasks);
    }
    /**
     * shpws the tasks for each user
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTasksUser(){
        $public =Task::where('private',0)->get();
        $myTasks=Task::where('user_id',auth()->id())->get();
        $followedTasks=UserFollowingTasks::where('user_id',auth()->id())->get();
        $myTasks=$public->merge($myTasks);
        $tasks=$myTasks-> add($followedTasks);
        return response()->json( $tasks);
    }
    /**
     * gets all the completed task for a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompleted(){
        $tasks=Task::where([['completed',1],['user_id',auth()->id()]])->get();
        return response()->json( $tasks);
    }
    /**
     * gets all the non-completed tasks for a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotCompleted(){
        $tasks=Task::where([['completed',0],['user_id',auth()->id()]])->get();
        return response()->json( $tasks);
    }
    /**
     * deletes a task
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(){
        $this->validate(request(),[
            'task_id'=>'required',
        ]);
        $task=Task::find(request('task_id'));
        if(auth()->id()==$task->user_id)
        {$task->delete();
            return response()->json('Task Deleted Successfully');}
            else {
                return response()->json("Task doesn't Belong To you");
        }
    }
}
