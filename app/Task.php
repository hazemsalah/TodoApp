<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  protected $fillable = [ 'body', 'user_id', 'private','completed','deadline' ];


  public function user(){

    return $this->belongsTo(User::class);

}

   public function files(){

   return $this->hasMany(File::class);

}


}
