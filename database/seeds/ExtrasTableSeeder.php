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
        $this->call(TecnicosTableSeeder::class);
        $user  = User::find(2);
        $user->attachRole(2); //técnico
        $this->call(InstrumentosTableSeeder::class);
        $this->call(FornecedorTableSeeder::class);
        $this->call(PecasTableSeeder::class);
        $this->call(SelosLacresTableSeeder::class);

        /*
        $this->call(KitsTableSeeder::class);
        $this->call(PecaKitsTableSeeder::class);
        $this->call(InsumosTableSeeder::class);
        */
        echo "\n*** Importacao realizada com sucesso em ".round((microtime(true) - $start), 3)."s ***"; exit;

    }
}
