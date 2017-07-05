<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Task;


class checkingRemainingTimeTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send{user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Notification when remaining eighty percent of the deadline';
public $task ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Task $task )
    {
        $this->task =$task;
        parent::__construct( );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deadline = $this->task->deadline;
        $startDate = $this->task->created_at;
        $totalTime = $deadline-$startDate;
        $eightyPercent= ($totalTime*80/100);
        $currentTime = \Carbon::now();
        $timeDifference = $deadline-$currentTime;

         if ($timeDifference==$eightyPercent){
             dd('Short time remaining');
         }
         else
         {
             dd('still time remaining ');
         }


    }
}
