<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon as Carbon;
use \App\Http\Controllers\FechamentoController as FechamentoController;

class NovoFechamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=NovoFechamentoTableSeeder
    public function run()
    {
        $start = microtime(true);
        $FechamentoController = new FechamentoController();
        echo "*** Iniciando os Fechamento ***";
        Carbon::setTestNow('2017-02-01 00:00:00');                        // set the mock (of course this could be a real mock object)
        $FechamentoController->run();
        Carbon::setTestNow('2017-03-01 00:00:00');                        // set the mock (of course this could be a real mock object)
        $FechamentoController->run();

        echo "\n*** NovoFechamentoTableSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}



