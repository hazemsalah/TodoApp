<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = array( 'body','user_id','private','completed','deadline');

    public function user (){
        return $this->belongsTo(User::class);
    }
    public  function scopeIncomplete($query){
        return $query->where('completed',0);
    }
    public function file(){
          return $this->hasMany(File::class);
    }

}
