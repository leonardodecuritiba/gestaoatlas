<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckParcelas::class,
        Commands\ExportClientes::class,
        Commands\ExportEquipamentos::class,
        Commands\ExportFornecedores::class,
        Commands\ExportGrupos::class,
        Commands\ExportMarcas::class,
        Commands\ExportNcm::class,
        Commands\ExportPecas::class,
        Commands\ExportRegioes::class,
        Commands\ExportSegmentos::class,
        Commands\ExportSegmentosFornecedores::class,
        Commands\ExportServicos::class,
        Commands\ExportTabelaPreco::class,
        Commands\ExportTabelaPrecoPeca::class,
        Commands\ExportTabelaPrecoServico::class,
        Commands\MakeExport::class,
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
        $schedule->command('backup:clean')->daily()->at('01:00');

        $schedule->command('backup:run')->daily()->at('12:00');
        $schedule->command('backup:run')->daily()->at('18:00');
        $schedule->command('backup:run')->daily()->at('02:00');

//        $schedule->call(function () {
//
//            $user = array(
//                'email' => "silva.zanin@gmail.com",
//                'name' => "TESTE SCHEDULE",
//                'mensagem' => "olá",
//            );
//            Mail::raw($user['mensagem'], function ($message) use ($user) {
//                $message->to($user['email'], $user['name'])->subject('Welcome!');
//                $message->from('xxx@gmail.com', 'Atendimento');
//            });
//        })->everyMinute();
    }
}
