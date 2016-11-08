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

$factory->define(App\PessoaJuridica::class, function (Faker\Generator $faker) {
    return [
        'cnpj'                      => $faker->randomNumber($nbDigits = 7).$faker->randomNumber($nbDigits = 7),
        'ie'                        => $faker->randomNumber($nbDigits = 7).$faker->randomNumber($nbDigits = 5),
        'isencao_ie'                => $faker->boolean,
        'razao_social'              => $faker->company,
        'nome_fantasia'             => $faker->company." ".$faker->companySuffix,
        'ativ_economica'            => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'sit_cad_vigente'           => $faker->randomElement($array = array ('HABILITADO','NÃO HABILITADO')),
        'sit_cad_status'            => $faker->randomElement($array = array ('ATIVO','INATIVO')),
        'data_sit_cad'              => $faker->dateTimeThisCentury($max = 'now')->format('d/m/Y'),
        'reg_apuracao'              => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'data_credenciamento'       => $faker->dateTimeThisCentury($max = 'now')->format('d/m/Y'),
        'ind_obrigatoriedade'       => 'OBRIGATORIEDADE TOTAL',
        'data_ini_obrigatoriedade'  => $faker->dateTimeThisCentury($max = 'now')->format('d/m/Y'),
    ];
});
/*
 */