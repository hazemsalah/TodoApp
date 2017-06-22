<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;


class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth')->except(['index','show','showTasksGuest']);
    }

    public function index(Tasks $tasks){
        $tasks=$tasks->all();
        return view('tasks.index',compact ('tasks'));
    }
    public function create(){

        return view('tasks.add');
    }
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
        return response()->json(['result' => $task]);
    }
    public function toggleComplete(){
        $task=Task::find(request('task_id'));

        if (\Gate::denies('taskOwner', $task)) {
            return response()->json("Task doesn't Belong To you");
        }

        $task->completed=!$task->completed;
        $task->save();
        return response()->json(['result' => $task]);

    }
    public function togglePrivate(){
        $task=Task::find(request('task_id'));
        if(\Gate::denies('taskOwner', $task)){
            return response()->json("Task doesn't Belong To you");
        }
        $task->private=!$task->private;
        $task->save();
        return response()->json(['result' => $task]);

    }
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
        return response()->json(['result' => $task]);

    }
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
            return response()->json(['result' => $task]);

    }
    public function showTasksGuest(){
        $tasks =Task::where('private',0)->get();
        return response()->json( $tasks);
    }
    public function showTasksUser(){
        $public =Task::where('private',0)->get();
        $myTasks=Task::where('user_id',auth()->id())->get();
        $tasks=$public->merge($myTasks);
        return response()->json( $tasks);
    }

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
