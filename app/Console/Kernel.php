<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\InserSearchList;
//use App\Http\Controllers\SearchController;

class Kernel extends ConsoleKernel
{

    
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Searchlist',
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        /*$schedule->command('queue:work')->cron('* * * * * *');*/
        //$schedule->command('store:searchresult')->everyMinute();
         $schedule->call(function () {
            $check_draw = new InserSearchList();
        })->dailyAt('00:01');

        /*$schedule->call('App\Http\Controllers\SearchController@test')
                ->everyMinute();*/

        //$schedule->call('SearchController@test')->dailyAt('00:01');



    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
