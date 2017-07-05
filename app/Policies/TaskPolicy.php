<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class TaskPolicy
 * @package App\Policies
 */
class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function togglePrivate(User $user, Task $task)
    {
        return $this->taskOwner($user, $task);
    }


    /**
     * checks that the user is the owner of the task
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function taskOwner(User $user, Task $task)
    {
        return $user->id === $task->user_id;
    }
}
