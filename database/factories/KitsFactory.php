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

$factory->define(App\Kit::class, function (Faker\Generator $faker) {
    return [
        'nome'              => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'descricao'         => $faker->sentence($nbWords = 10, $variableNbWords = true),
    ];
});
/*
$factory->defineAs(App\Insumo::class, 'pecas', function ($faker) {
    return [
        'idpeca'    => $faker->numberBetween($min = 1, $max = 50),
        'idkit'     => NULL,
        'tipo'      => $faker->boolean,
    ];
});

$factory->defineAs(App\Insumo::class, 'kits', function ($faker) {
    return [
        'idpeca'    => NULL,
        'idkit'     => $faker->numberBetween($min = 1, $max = 3),
        'tipo'      => $faker->boolean,
    ];
});
/*
 */