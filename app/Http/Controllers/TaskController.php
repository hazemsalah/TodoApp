<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TaskController extends Controller
{

  public function __construct(){

  $this->middleware('auth')->except(['index']);

}

// public function show(Task $task){
//
//
//       return $task;
//       //return view('task.show',compact('task'));
//   }

  public function taskUser(){

     $publicTasks = Task::where('private',0)->get();

     $userTasks = Task::where('user_id',auth()->id())->get();

     $allTasks = $publicTasks->merge($userTasks);

     return response()->json($allTasks);

}

  public function taskGuest()
{
    //$tasks = Task::all();

    $tasks = Task::where('private',0)->get();

    //return $tasks;

    return view('task.index',compact('tasks'));
}
public function create(){

    return view('task.addTask');
}

   public function store(){

      $this->validate(request(),[

      'body' => 'required',

      'deadline' => ' required'

 ]);

      $task = Task::create([

         'body' => request('body'),

         'user_id' => auth()->id(),

         'deadline' => request('deadline')


 ]);

 session()->flash('message','Your Task has now been created :)');



   return redirect('/');


   }

   public function update()
   {

          $this->validate(request(), [

                'id' => 'required',

            ]);

       $id = request('id');

       $task = Task::findOrFail($id);


      if(auth()->id() == $task->user_id)
     {

       $input = request()->all();

       $task->fill($input)->save();

       session()->flash('message', 'Task successfully updated :)');

       return redirect('/');
     }
     else{

       return('You cannot update this task');
     }
   }

   public function destroy()
 {

     $id = request('id');

     $task = Task::findOrFail($id);

      if(auth()->id() == $task->user_id)
     {

     $task->delete();

      session()->flash('message','Task successfully deleted!');

     return redirect('/');
    }

    else{

         return('You cannot delete this task');
        //session()->flash('message','You cannot delete this task');

    }
 }

}
