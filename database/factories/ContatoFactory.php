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

$factory->define(App\Contato::class, function (Faker\Generator $faker) {
    return [
        'telefone'      => $faker->randomNumber($nbDigits = 6).$faker->randomNumber($nbDigits = 4),
        'celular'       => $faker->randomNumber($nbDigits = 6).$faker->randomNumber($nbDigits = 4),
        'skype'         => $faker->name,
        'cep'           => $faker->randomNumber($nbDigits = 8),
        'estado'        => $faker->word,
        'cidade'        => $faker->word,
        'bairro'        => $faker->streetName,
        'logradouro'    => $faker->streetName,
        'numero'        => $faker->randomNumber($nbDigits = 4),
        'complemento'   => $faker->word
    ];
});
/*
 */