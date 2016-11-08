<?php

use Illuminate\Database\Seeder;

class FornecedorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=FornecedorTableSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Fornecedor::class,3)->create();
        echo "\n*** Fornecedor completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
