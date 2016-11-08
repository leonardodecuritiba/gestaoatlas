<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Colaborador::class, function (Faker\Generator $faker) {
    return [
        'idcontato' => function () {
            return factory(App\Contato::class)->create()->idcontato;
        },
        'iduser' => function () {
            return factory(App\User::class)->create()->iduser;
        },
        'cpf'               => $faker->randomNumber($nbDigits = 7).$faker->randomNumber($nbDigits = 5),
        'rg'                => $faker->randomNumber($nbDigits = 4).$faker->randomNumber($nbDigits = 4),
        'nome'              => $faker->name,
        'data_nascimento'   => $faker->dateTimeThisCentury($max = 'now')->format('d/m/Y'),
        'cnh'               => $faker->image($dir = storage_path('uploads/colaboradores/'), $width = 640, $height = 480, 'technics', false),
        'carteira_trabalho' => $faker->image($dir = storage_path('uploads/colaboradores/'), $width = 640, $height = 480, 'technics', false)
    ];
});
/*
 */