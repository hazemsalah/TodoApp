<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Task;
use App\UserFollowingTasks;

/**
 * Class UpdateTask
 * @package App\Notifications
 */
class UpdateTask extends Notification
{
    use Queueable;

    /**
     * @var UserFollowingTasks
     */
    public $followedTask;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserFollowingTasks $followedTask)
    {
        $this->followedTask = $followedTask;
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
            "A Task You are following has been updated "
       ]));

   }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Task.'.$this->followedTask->task_id);
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

        ];
    }
}
