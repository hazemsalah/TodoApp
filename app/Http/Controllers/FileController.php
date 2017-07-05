<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends Controller
{
    /**
     * uploads a file to a certain task
     * @request('file') required
     * @request('task_id') required
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(){
        $this->validate(request(),[
            'task_id'=>'required',
            'file'=>'required'
        ]);
    $file=request('file');
    File::create([
        'task_id'=>request('task_id'),
        'path'=>'/uploads/'.$file->getClientOriginalName()
    ]);
    $destinationPath = 'uploads';
    $file->move($destinationPath,$file->getClientOriginalName());
        return response()->json(['Your file has been uploaded']);
}
}
