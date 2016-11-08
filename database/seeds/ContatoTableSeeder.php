<?php

use Illuminate\Database\Seeder;

class ContatoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=ContatoTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Contato::class)->create();
        echo "\n*** Contato completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
