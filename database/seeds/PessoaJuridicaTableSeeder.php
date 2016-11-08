<?php

use Illuminate\Database\Seeder;

class PessoaJuridicaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=PessoaJuridicaTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\PessoaJuridica::class)->create();
        echo "\n*** PessoaJuridica completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
