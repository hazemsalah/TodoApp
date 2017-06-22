<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['body', 'user_id', 'private', 'compeleted', 'deadline'];

    public function user()

    {
        return $this->belongsTo(User::class);

    }

    public function task(){
        return $this->hasMany(File::class);
    }
}

