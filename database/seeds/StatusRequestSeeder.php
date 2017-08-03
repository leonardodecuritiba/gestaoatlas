<?php

use Illuminate\Database\Seeder;

class StatusRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=StatusRequestSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            ['description' => 'AGUARDANDO'],
            ['description' => 'ACEITA'],
            ['description' => 'NEGADA']
        ];
        \App\Models\Requests\StatusRequest::insert($data);
        echo "\n*** StatusRequest completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
