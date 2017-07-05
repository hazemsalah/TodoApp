<?php

namespace App\Notifications;

use App\UserFollowingTasks;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class UpdateTask extends Notification
{
    use Queueable;
    /**
     * @var UserFollowingTasks
     */
    public $followers;

    public function __construct(UserFollowingTasks $followers)
    {

        $this->followers = $followers;
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
             'task you are following is updated'
        ]));
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Task.'.$this->followers->task_id);
    }

}
