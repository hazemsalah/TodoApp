<?php

namespace App\Notifications;

use App\User;
use App\Task;
use App\UserFollowingTasks;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

/**
 * Class Followers
 * @package App\Notifications
 */
class Followers extends Notification
{
    use Queueable;
    /**
     * @var User
     */
    public $user;

    /**
     * @var Task
     */
    public $task;

    /**
     * Followers constructor.
     * @param User $user
     * @param Task $task
     */
    public function __construct(User $user, Task $task)
    {
        $this->user=$user;
        $this->task=$task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {

                return (new BroadcastMessage([

           $this->user->name." followed your task ".$this->task->body

        ]));

    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn()
    {

        return new PrivateChannel('App.Task.'.$this->task->id);


    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
