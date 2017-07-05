<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FileController extends Controller
{

    /**
     * upload a file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile()
    {
        $this->validate(request(), [
            'filename' => 'required',
            'task_id' => 'required'
        ]);
        $file = request('filename');
        $destinationPath = 'uploads';
        $file->move($destinationPath, $file->getClientOriginalName());
        File::create([
            'filename' =>  "/uploads/".$file->getClientOriginalName(),
            'task_id' => request('task_id')
        ]);
        return response()->json('Your File was uploaded successfully');
    }
}
