<?php

namespace App\Listeners;

use App\Events\SendReminders;
use App\Notifications\reminder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendReminders  $event
     * @return void
     */
    public function handle(SendReminders $event)
    {

    }
}
