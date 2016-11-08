<?php

use Illuminate\Database\Seeder;

class PecaKitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=PecaKitsTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\PecaKit::class,10)->create();
        echo "\n*** PecaKit completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
