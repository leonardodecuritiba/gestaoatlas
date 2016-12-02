<?php

use Illuminate\Database\Seeder;

class TecnicosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=TecnicosTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os TecnicosTableSeeder ***";
        factory(App\Tecnico::class)->create();
        echo "\n*** Tecnico completo em " . round((microtime(true) - $start), 3) . "s ***\n";
    }
}
