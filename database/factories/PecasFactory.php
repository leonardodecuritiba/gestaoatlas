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

$factory->define(App\Peca::class, function (Faker\Generator $faker) {
    return [
        'idfornecedor'      => $faker->numberBetween($min = 1, $max = 3),
        'idmarca'           => $faker->numberBetween($min = 1, $max = 4),
        'idunidade'         => $faker->numberBetween($min = 1, $max = 5),
        'idgrupo'           => $faker->numberBetween($min = 1, $max = 2),
        'tipo'              => $faker->randomElement($array = array ('peca','produto')),
        'codigo'            => $faker->randomNumber($nbDigits = 5),
        'codigo_auxiliar'   => $faker->randomNumber($nbDigits = 3),
        'codigo_barras'     => $faker->ean13,
        'descricao'         => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'descricao_tecnico' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'sub_grupo'         => $faker->word,
        'garantia'          => $faker->numberBetween($min = 1, $max = 12),
        'foto'              => $faker->image($dir = storage_path('uploads/instrumentos/'), $width = 640, $height = 480, 'technics', false),
        'comissao_tecnico'  => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 10),
        'comissao_vendedor' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 10),
        'custo_final'       => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000),
    ];
});
/*
 */