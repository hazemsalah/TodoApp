<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFollowingTasks extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['task_id', 'user_id'];
}
