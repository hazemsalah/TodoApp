<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');

        Broadcast::channel('App.Task.{{task_id}}', function ($user, $taskId) {

               return $user->id== App\Task::find($taskId)->user_id;
        });


    }
}
