<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['task_id', 'filename'];

    public function task()
    {

        return $this->belongsTo(Task::class);
    }
}
