<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckParcelas::class
        // Commands\Inspire::class,
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

        $schedule->command('command:check_parcelas')->daily();

        $schedule->call(function () {

            $user = array(
                'email' => "silva.zanin@gmail.com",
                'name' => "TESTE SCHEDULE",
                'mensagem' => "olá",
            );
            Mail::raw($user['mensagem'], function ($message) use ($user) {
                $message->to($user['email'], $user['name'])->subject('Welcome!');
                $message->from('xxx@gmail.com', 'Atendimento');
            });
        })->everyMinute();
    }
}
