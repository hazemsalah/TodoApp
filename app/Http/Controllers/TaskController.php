<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TaskController extends Controller
{
    public function store(){

        $this->validate(request(), [

            'body' => 'required',
            'deadline' => 'required'

        ]);
        $task =Task::create([
                'body'=>request('body') ,
                'user_id'=>auth()->id(),
                'deadline' => request('deadline')
                ]);
        return ("Task added");


    }


    public function toggleCompleted(Request $request){
        $this->validate(request(),[
           'completed' => 'required'
        ]);

        $task = Task::find($request->input('id'));
        if(auth()->id() == $task->user_id) {
            $task->completed = !$task->completed;
            $task->save();
            return ("YAY completed EDITED");
        }
        else{

            return ("Task Not yours");
        }

    }


    public function togglePrivate(Request $request){
        $this->validate(request(), [
            'private' => 'required'
        ]);

        $task = Task::find($request->input('id'));
        if(auth()->id() == $task->user_id) {
             $task->private = ! $task->private;
             $task->save();
             return ("YAY private EDITED");
        }
        else{
            return ("Task Not yours");
        }

    }

    public function updateBody(){

        $this->validate(request(), [
            'body' => 'required'
        ]);
        $task = Task::find(request('id'));
        if(auth()->id() == $task->user_id) {
            $task->body = request('body');
            $task->save();
            return("body is updated");
        }
        else{
            return ("This task is not yours");
        }
    }

    public function updateDeadline(){

        $this->validate(request(), [
            'deadline' => 'required'
        ]);
        $task = Task::find(request('id'));
        if(auth()->id() == $task->user_id) {
            $task->deadline = request('deadline');
            $task->save();
            return("deadline is updated");
        }
        else{
            return ("This task is not yours");
        }
    }


    public function deleteTask(){

        $task = Task::find(request('id'));
        if(auth()->id() == $task->user_id) {
            $task->delete();
            return ("YAY task is deleted");
        }
        else{
            return ("This task doesnt belong to you");
        }

    }

    public function tasksUser(){
        $publis_tasks = Task::where('private', 0 )->get();
        $my_private_tasks = Task::where([
            ['private', 1],
            ['user_id', auth()->id()],
        ])->get();

        $result = $public_tasks->merge($my_private_tasks);
        return response()->json($result);
    }

    public function tasksGuest(){
        $public_tasks = Task::where("private", 0)->get();
        return response()->json($public_tasks);
    }


}
