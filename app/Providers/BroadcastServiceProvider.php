<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;


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

        Broadcast::channel('App.Task.{task_id}', function($user, $taskId){
            return $user->id = App\Task::find($taskId)->user_id;
        });

        Broadcast::channel('App.Task.{task_body}', function($user, $taskId){
            return true;
        });



    }
}
