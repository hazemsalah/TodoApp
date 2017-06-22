<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FileController extends Controller
{

  public function uploadFile(){

    $this->validate(request(),[

     'path' => 'required',

     'task_id' => ' required'

   ]);

      $file = request('path');

      $destinationPath = 'uploads';

      $file->move($destinationPath,$file->getClientOriginalName());


      $filedb = File::create([

        'path' => "/uploads/".$file->getClientOriginalName(),

       'task_id' => request('task_id')

     ]);

     session()->flash('message','Your File has now been uploaded ');

     return redirect('/');

  }

}
