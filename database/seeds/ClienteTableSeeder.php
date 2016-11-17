<?php

use Illuminate\Database\Seeder;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=ClienteTableSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        factory(App\Cliente::class, 2)->create();
        echo "\n*** Fornecedor completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
