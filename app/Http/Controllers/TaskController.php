<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TaskController extends Controller
{
    public function store()
    {

        $this->validate(request(), [

            'body' => 'required',
            'deadline' => 'required'

        ]);
        $task =Task::create([
                'body'=>request('body') ,
                'user_id'=>auth()->id(),
                'deadline' => request('deadline')
                ]);
        return response()->json("Task added");
    }


    public function toggleCompleted()
    {
        $this->validate(request(), [
           'id' => 'required'
        ]);

        $task = Task::find(request()->input('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->completed = !$task->completed;
            $task->save();
            return response()->json("YAY completed EDITED");
        }
            return response()->json("Task Not yours");
    }


    public function togglePrivate()
    {
        $this->validate(request(), [
            'private' => 'required'
        ]);

        $task = Task::find(request()->input('id'));
        if (\Gate::allows('taskOwner', $task)) {
             $task->private = ! $task->private;
             $task->save();
             return response()->json("YAY private EDITED");
        } else {
            return response()->json("Task Not yours");
        }
    }

    public function updateBody()
    {

        $this->validate(request(), [
            'body' => 'required'
        ]);
        $task = Task::find(request('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->body = request('body');
            $task->save();
            return response()->json("body is updated");
        } else {
            return response()->json("This task is not yours");
        }
    }

    public function updateDeadline()
    {

        $this->validate(request(), [
            'deadline' => 'required'
        ]);
        $task = Task::find(request('id'));
        if (\Gate::allows('taskOwner', $task)) {
            $task->deadline = request('deadline');
            $task->save();
            return response()->json("deadline is updated");
        } else {
            return response()->json("This task is not yours");
        }
    }


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

    public function tasksUser()
    {
        $public_tasks = Task::where('private', 0)->get();
        $my_private_tasks = Task::where([
            ['private', 1],
            ['user_id', auth()->id()],
        ])->get();

        $result = $public_tasks->merge($my_private_tasks);
        return response()->json($result);
    }

    public function tasksGuest()
    {
        $public_tasks = Task::where("private", 0)->get();
        return response()->json($public_tasks);
    }


}
