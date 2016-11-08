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

$factory->define(App\Tecnico::class, function (Faker\Generator $faker) {
    return [
        'idcolaborador' => function () {
            return factory(App\Colaborador::class)->create()->idcolaborador;
        },
        'carteira_imetro'   => $faker->image($dir = storage_path('uploads/tecnicos/'), $width = 640, $height = 480, 'technics', false),
        'carteira_ipem'     => $faker->image($dir = storage_path('uploads/tecnicos/'), $width = 640, $height = 480, 'technics', false)
    ];
});
/*
 */