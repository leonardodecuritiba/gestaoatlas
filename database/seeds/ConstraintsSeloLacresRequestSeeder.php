<?php

use Illuminate\Database\Seeder;

class ConstraintsSeloLacresRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=ConstraintsSeloLacresRequestSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            //máximo número de lacres que usuário pode ter antes de requisitar novos
            ['meta_key' => 'requests_max_lacres', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
            //máximo número de selos que usuário pode ter antes de requisitar novos
            ['meta_key' => 'requests_max_selos', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
            //máximo número de lacres que usuário pode ter antes de requisitar novos
            ['meta_key' => 'requests_max_lacres_req', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
            //máximo número de selos que usuário pode ter antes de requisitar novos
            ['meta_key' => 'requests_max_selos_req', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
        ];
        \App\Models\Ajustes\Ajuste::insert($data);
        echo "\n*** StatusRequest completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
