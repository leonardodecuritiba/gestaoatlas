<?php

use Illuminate\Database\Seeder;

class SelosLacresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=SelosLacresTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $ini = 20007206;
        for($i=$ini;$i<$ini + 100;$i++){
            $data[] = [
                'idtecnico' =>1,
                'numeracao' =>$i
            ];
        }
        App\Selo::insert($data);

        $data = NULL;
        $ini = 1700;
        for($i=$ini;$i<$ini + 100;$i++){
            $data[] = [
                'idtecnico' =>1,
                'numeracao' =>$i
            ];
        }
        App\Lacre::insert($data);
        echo "\n*** SelosLacres completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
