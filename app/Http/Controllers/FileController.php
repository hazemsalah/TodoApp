<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FileController extends Controller
{
public function upload(){
    $file=request('file');
    //dd($file->getClientOriginalName());
    File::create([
        'task_id'=>request('task_id'),
        'path'=>'/uploads/'.$file->getClientOriginalName()
    ]);
    $destinationPath = 'uploads';
    $file->move($destinationPath,$file->getClientOriginalName());
}
}
