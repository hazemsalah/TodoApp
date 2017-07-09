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
        Broadcast::channel('App.Task.{task_id}', function ($user, $taskId) {
            return $user->id == App\Task::find($taskId)->user_id;
        });
        Broadcast::channel('App.Task.{task_name}', function () {
            return true;

        });

        Broadcast::channel('App.Comment.{comment_id}', function ($user) {
            return true;
        });

    }
}
