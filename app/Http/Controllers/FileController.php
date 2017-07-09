<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
//use Intervention\Image\Image as Image;
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
            'comment_id'=>'required',
            'file'=>'required'
        ]);

        $file=request('file');


        if ($file->getMimeType()=="image/jpeg"||$file->getMimeType()=="image/png")
        {
            if( getimagesize($file)[0]>=1500 ){
                $img =  \Image::make($file->getRealPath());
                $img->resize(800, 800);
                $img->save($file->getRealPath());
            }
        }
        $path = $file->storeAs('uploads',$file->getClientOriginalName());
        File::create([
        'comment_id'=>request('comment_id'),
        'path'=>$path
    ]);

        return response()->json(['Your file has been uploaded']);
}
}
