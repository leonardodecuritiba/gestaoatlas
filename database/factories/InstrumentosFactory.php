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

$factory->define(App\Instrumento::class, function (Faker\Generator $faker) {
    return [
        'idcliente'                 => $faker->numberBetween($min = 1, $max = 2),
        'idmarca'                   => $faker->numberBetween($min = 1, $max = 4),
        'idcolaborador_criador'     => 1,
        'idcolaborador_validador'   => 1,
        'validated_at'              => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'),
        'descricao'                 => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'foto'                      => $faker->image($dir = storage_path('uploads/instrumentos/'), $width = 640, $height = 480, 'technics', false),
        'modelo'                    => $faker->word,
        'numero_serie'              => $faker->randomNumber($nbDigits = 7).'-'.$faker->randomNumber($nbDigits = 2),
        'inventario'                => $faker->randomNumber($nbDigits = 8),
        'patrimonio'                => $faker->randomNumber($nbDigits = 6),
        'ano'                       => $faker->numberBetween($min = 1990, $max = 2016),
        'portaria'                  => $faker->randomNumber($nbDigits = 3).'/'.$faker->numberBetween($min = 1990, $max = 2016),
        'divisao'                   => $faker->randomNumber($nbDigits = 3),
        'capacidade'                => $faker->numberBetween($min = 1, $max = 100)."Kg",
        'ip'                        => $faker->ipv4,
        'endereco'                  => $faker->numberBetween($min = 1, $max = 20),
        'setor'                     => $faker->numberBetween($min = 100, $max = 1000),
    ];
});
/*
 */