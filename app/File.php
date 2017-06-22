<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{   protected $fillable = array( 'path','task_id');
    public function task (){
        return $this->belongsTo(Task::class);
    }
}
