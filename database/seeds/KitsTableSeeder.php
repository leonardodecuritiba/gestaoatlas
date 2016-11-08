<?php

use Illuminate\Database\Seeder;

class KitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=KitsTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Kit::class,3)->create();
        echo "\n*** Kit completo em ".round((microtime(true) - $start), 3)."s ***";
    }
    //php artisan db:seed --class=InsumosTableSeeder
    //$start = microtime(true);
    //echo "*** Iniciando os Seeders ***";
    //factory(App\Insumo::class,'pecas',10)->create();
    //factory(App\Insumo::class,'kit',10)->create();
    //echo "\n*** InsumosPeca completo em ".round((microtime(true) - $start), 3)."s ***";
}
