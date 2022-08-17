<?php

namespace App\Console;

use App\Models\JobExecution;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->commandWithLogs($schedule, 'sync')
          ->withoutOverlapping()
          ->monthly();

        $this->commandWithLogs($schedule, 'sync:listings')
          ->withoutOverlapping()
          ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    public function success($name)
    {
      return function ($output) use ($name) {
        JobExecution::create([
          'name' => $name,
          'log' => $output,
          'status' => 'succeeded',
        ]);
      };
    }

    public function failure($name)
    {
      return function ($output) use ($name) {
        JobExecution::create([
          'name' => $name,
          'log' => $output,
          'status' => 'failed',
        ]);
      };
    }

    protected function commandWithLogs(Schedule $schedule, string $name)
    {
      return $schedule->command($name)
        ->runInBackground()
        ->onSuccessWithOutput($this->success($name))
        ->onFailureWithOutput($this->failure($name));
    }
}
