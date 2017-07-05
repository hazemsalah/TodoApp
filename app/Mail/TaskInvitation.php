<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;
use App\User;

/**
 * Class TaskInvitation
 * @package App\Mail
 */
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
     * Create a new message instance.
     *
     * @return void;
     * public $user;
     * public $task;
     */
    public function __construct( User $user, Task $task)
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
        return $this->view('emails.taskInvitation');
    }
}
