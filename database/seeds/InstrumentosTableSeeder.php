<?php

use Illuminate\Database\Seeder;

class InstrumentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=InstrumentosTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Instrumento::class,10)->create();
        echo "\n*** Instrumento completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
