<?php

namespace App\Providers;

use App\Console\Commands\DatabaseQueueMonitorCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Queue::failing(function (JobFailed $event) {
            report($event);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                DatabaseQueueMonitorCommand::class,
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping(10);
            $schedule->command('queue:restart')->hourly();
            $schedule->command('queue:db-monitor')->everyTenMinutes();
            // $schedule->job(new Heartbeat)->daily();
        });
    }

    public function register()
    {
        //
    }
}
