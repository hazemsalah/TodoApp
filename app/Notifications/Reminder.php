<?php

namespace App\Notifications;

use App\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

/**
 * Class Reminder
 * @package App\Notifications
 */
class Reminder extends Notification
{
    /**
     * @var Task
     */
    public $task;


    /**
     * Reminder constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via()
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

            "Your Task has been created"

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
