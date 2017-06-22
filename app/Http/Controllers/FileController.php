<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FileController extends Controller
{
    public function uploadFile(){

        $this->validate(request(),[
            'filename' => 'required',
            'task_id' => 'required'
        ]);
        $file = request('filename');
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());
        $filedb = File::create([
            'filename' =>  "/uploads/".$file->getClientOriginalName(),
            'task_id' => request('task_id')
        ]);
    }
}
