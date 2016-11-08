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

//        'idcontato',
//        '',
//        'idpfisica',

$factory->define(App\Cliente::class, function (Faker\Generator $faker) {
    return [
        'idcontato' => function () {
            return factory(App\Contato::class)->create()->idcontato;
        },
        'idpjuridica' => function () {
            return factory(App\PessoaJuridica::class)->create()->idpjuridica;
        },
        'idsegmento_fornecedor' => $faker->numberBetween($min = 1, $max = 4),
        'grupo'                 => $faker->word,
        'email_orcamento'       => $faker->email,
        'nome_responsavel'      => $faker->name,
        'distancia'             => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000),
        'pedagios'              => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000),
        'outros_custos'         => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000),
    ];
});
/*
 */