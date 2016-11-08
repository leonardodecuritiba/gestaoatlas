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

$factory->define(App\PecaKit::class, function (Faker\Generator $faker) {
    $qtd = $faker->numberBetween($min = 1, $max = 5);
    $val = $faker->randomFloat($nbMaxDecimals = 2, $min = 100, $max = 10000);
    return [
        'idkit'                 => $faker->numberBetween($min = 1, $max = 3),
        'idpeca'                => $faker->numberBetween($min = 1, $max = 50),
        'quantidade'            => $qtd,
        'valor_unidade'         => $val,
        'valor_total'           => $qtd*$val,
        'descricao_adicional'   => $faker->sentence($nbWords = 10, $variableNbWords = true),
    ];
});
/*
 */