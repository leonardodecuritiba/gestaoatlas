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
        $file = storage_path('uploads').'\import\import.xlsx';
        echo "\n*** Upload completo em ".round((microtime(true) - $start), 3)."s ***";
        // Loop through all rows
        set_time_limit(3600);
        $reader = Excel::load($file, function($reader) {})->ignoreEmpty();
        $reader->each(function($sheets) {
            $table = $sheets->getTitle();
            foreach ($sheets->toArray() as $sheet){
                $sheet['created_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                DB::table($table)->insert($sheet);
            }
        });
        $dump = ['ncm_dump','users_clientes_fornecedores_dump'];
        foreach($dump as $d){
            DB::unprepared(file_get_contents(storage_path('uploads').'\import\\'.$d.'.sql'));
        }

        DB::table('ajustes')->insert(['meta_key'=>'custo_km','meta_value'=>'3.50']);
        echo "\n*****************************************************"; exit;
        echo "\n*** Importacao realizada com sucesso em ".round((microtime(true) - $start), 3)."s ***"; exit;
    }
}