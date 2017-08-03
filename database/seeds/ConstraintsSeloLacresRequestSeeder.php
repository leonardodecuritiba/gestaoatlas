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

        $faker = Faker\Factory::create();
        $user = App\User::create([
            'email' => 'gestao@atlastecnologia.com.br',
            'password' => bcrypt('123'),
        ]);
        $contato = App\Contato::create();
        $colaborador = App\Colaborador::create([
            'idcontato' => $contato->idcontato,
            'iduser' => $user->iduser,
            'cpf' => $faker->randomNumber($nbDigits = 7) . $faker->randomNumber($nbDigits = 5),
            'rg' => $faker->randomNumber($nbDigits = 4) . $faker->randomNumber($nbDigits = 4),
            'nome' => 'Gestor Selo/Lacre',
            'data_nascimento' => $faker->dateTimeThisCentury($max = 'now')->format('d/m/Y'),
            'cnh' => 'X',
            'carteira_trabalho' => 'X',
        ]);
        App\Tecnico::create([
            'idcolaborador' => $colaborador->idcolaborador,
            'carteira_imetro' => 'X',
            'carteira_ipem' => 'X',
        ]);

        echo "\n*** ConstraintsSeloLacresRequestSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
