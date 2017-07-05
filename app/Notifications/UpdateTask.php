<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\broadcastMessage;

/**
 * Class UpdateTask
 * @package App\Notifications
 */
class UpdateTask extends Notification
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
     * UpdateTask constructor.
     * @param Task $task
     * @param User $user
     */
    public function __construct(Task $task, User $user)
    {
        $this->task=$task;
        $this->user=$user;
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
     * @return \Illuminate\Notifications\Messages\broadcastMessage
     */
    public function toBroadcast($notifiable)
    {

        return (new BroadcastMessage([

            $this->user->name." updated task ".$this->task->body

        ]));

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
