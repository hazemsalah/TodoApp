<?php

namespace App\Notifications;

use App\Task;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class FollowTask extends Notification
{
    use Queueable;
    /**
     * @var Task
     */
    public $task;
    /**
     * @var User
     */
    public $user;

    /**
     * FollowTask constructor.
     * @param Task $task
     * @param User $user
     */
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            $this->user->name .  'is watching your task whose name is '. $this->task->body
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
