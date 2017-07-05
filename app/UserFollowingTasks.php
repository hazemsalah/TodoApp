<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFollowingTasks
 * @package App
 */
class UserFollowingTasks extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'task_id'
    ];

}
