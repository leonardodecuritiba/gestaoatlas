<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = microtime(true);

        echo "*** Iniciando o Upload ***";
//        $dump = ['ncm_dump','gestaoatlas'];
        $dump = ['ncm_dump'];
        foreach($dump as $d){
            DB::unprepared(DB::raw(file_get_contents(storage_path('uploads') . '\import\\' . $d . '.sql')));
            echo "\n Importacao (" . ('\import\\' . $d . '.sql') . ")***************************************************";
        }
        echo "\n*****************************************************";
        echo "\n*** Importacao realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";
    }
}