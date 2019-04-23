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


	    Commands\ExportInstrumentBrands::class,
	    Commands\ExportInstrumentModels::class,
	    Commands\ExportPams::class,
	    Commands\ExportInstrumentSetors::class,
	    Commands\ExportInstrumentos::class,

	    Commands\ExportUsers::class,
        Commands\ExportLacres::class,
        Commands\ExportSelos::class,

        Commands\ExportPagamentos::class,
        Commands\ExportFaturamentos::class,
	    Commands\ExportOrdemServicos::class,
	    Commands\ExportAparelhoManutencao::class,
	    Commands\ExportSeloInstrumento::class,
	    Commands\ExportLacreInstrumento::class,

	    Commands\ExportServicosPrestados::class,
	    Commands\ExportPecasUtilizadas::class,

	    Commands\ExportRequests::class,
	    Commands\ExportParcelas::class,


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

        $schedule->command('command:check_parcelas')->daily();
        $schedule->command('backup:clean')->daily()->at('01:00');

        $schedule->command('backup:run')->daily()->at('12:00');
        $schedule->command('backup:run')->daily()->at('18:00');
        $schedule->command('backup:run')->daily()->at('02:00');

    }
}
