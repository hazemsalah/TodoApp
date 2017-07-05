<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\UserFollowingTasks;
use App\Task;

use App\User;

/**
 * Class FollowTask
 * @package App\Notifications
 */
class FollowTask extends Notification
{
    use Queueable;
    /**
     * @var UserFollowingTasks
     */
    public $task;
    /**
     * @var
     */
    public $userFollowingtask;
    /**
     * @var User
     */
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, UserFollowingTasks $task)
    {

      // $this ->$userFollowingtask=$$userFollowingtask;
        $this->task=$task;
        $this->user =$user;
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
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
           $this->user->name. "  Has followed your task "
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


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
