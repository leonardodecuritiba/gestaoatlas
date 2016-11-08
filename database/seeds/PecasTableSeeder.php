<?php

use Illuminate\Database\Seeder;

class PecasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=PecasTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Peca::class,50)->create();
        echo "\n*** Peca completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
