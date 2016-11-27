<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ExtrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=ExtrasTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders Extras ***";
        $this->call(InstrumentosTableSeeder::class);
        $this->call(FornecedorTableSeeder::class);
        $this->call(PecasTableSeeder::class);
        $this->call(SelosLacresTableSeeder::class);
        echo "\n*** Importacao realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }
}
