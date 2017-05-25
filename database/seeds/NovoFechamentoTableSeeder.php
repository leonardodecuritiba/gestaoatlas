<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon as Carbon;
use \App\Http\Controllers\FaturamentoController as FechamentoController;

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
        $FechamentoController = new FaturamentoController();
        $FechamentoController->run_teste();

        echo "\n*** NovoFechamentoTableSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}



