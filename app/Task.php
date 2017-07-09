<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App
 */
class Task extends Model
{
    /**
     * @var array
     */
    protected $fillable = array( 'body','user_id','private','completed','deadline');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (){
        return $this->belongsTo(User::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public  function scopeIncomplete($query){
        return $query->where('completed',0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function file(){
          return $this->hasMany(File::class);
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function comments (){
//        return $this->hasMany(Comment::class);
//    }
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

}
