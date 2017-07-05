<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;
use App\Task;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */

    public $user;
    /**
     * @var Task
     */
    public $task;

    /**
     * TaskInvitation constructor.
     * @param User $user
     * @param Task $task
     */
    public function __construct(User $user, Task $task)
    {
        $this->user=$user;
        $this->task=$task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.invitation');
    }
}
