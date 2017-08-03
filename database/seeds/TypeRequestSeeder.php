<?php

use Illuminate\Database\Seeder;

class TypeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=TypeRequestSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            ['description' => 'SELOS'],
            ['description' => 'LACRES'],
            ['description' => 'PADRÕES'],
            ['description' => 'FERRAMENTAS'],
            ['description' => 'EQUIPAMENTOS'],
            ['description' => 'VEÍCULOS'],
        ];
        \App\Models\Requests\TypeRequest::insert($data);
        echo "\n*** TypeRequestSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
