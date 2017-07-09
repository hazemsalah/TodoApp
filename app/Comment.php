<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'task_id', 'votes', 'body', 'user_id'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task (){
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies (){
        return $this->hasMany(Reply::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (){
        return $this->belongsTo(User::class);
    }

    public function hasVotes()
    {
        return $this->morphMany('App\Vote', 'voteable');
    }

}
