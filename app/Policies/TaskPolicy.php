<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
     * Handles the ability to toggle a task
     *
     * @param User $user
     * @param Task $task
     *
     * @return bool
     */
    public function toggleComplete(User $user, Task $task)
    {
        return $this->taskOwner($user, $task);
    }
    public function togglePrivate(User $user, Task $task)
    {
        return $this->taskOwner($user, $task);
    }


    public function taskOwner(User $user, Task $task)
    {
        return $user->id === $task->user_id;
    }
}
