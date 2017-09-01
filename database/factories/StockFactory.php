<?php

/*
|--------------------------------------------------------------------------
| Stocks Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define( \App\Models\Inputs\Tool::class, function ( Faker\Generator $faker ) {
	return [
		'idbrand'     => $faker->numberBetween( $min = 1, $max = 3 ),
		'idunit'      => 9,
		'idcategory'  => $faker->numberBetween( $min = 1, $max = 3 ),
		'description' => $faker->text( 50 ),
		'cost'        => $faker->randomFloat( $nbMaxDecimals = 2, $min = 0, $max = 100 ),
	];
} );

$factory->define( \App\Models\Inputs\Pattern::class, function ( Faker\Generator $faker ) {
	return [
		'idbrand'     => $faker->numberBetween( $min = 1, $max = 3 ),
		'idunit'      => 1,
		'description' => $faker->text( 50 ),
		'measure'     => $faker->randomFloat( $nbMaxDecimals = 2, $min = 0, $max = 100 ),
		'cost'        => $faker->randomFloat( $nbMaxDecimals = 2, $min = 0, $max = 100 ),
		'class'       => $faker->word,
	];
} );

$factory->define( \App\Models\Inputs\Instrument::class, function ( Faker\Generator $faker ) {
	return [
		'idbase'        => $faker->numberBetween( $min = 3, $max = 39 ),
		'serial_number' => $faker->numberBetween( 1000000, 2000000 ),
		'inventory'     => $faker->numberBetween( 1000000, 2000000 ),
		'year'          => $faker->date( 'Y' ),
	];
} );

$factory->define( \App\Models\Inputs\Equipment::class, function ( Faker\Generator $faker ) {
	return [
		'idbrand'       => $faker->numberBetween( $min = 1, $max = 50 ),
		'serial_number' => $faker->numberBetween( 1000000, 2000000 ),
		'description'   => $faker->text( 50 ),
		'model'         => $faker->text( 50 ),
		'photo'         => $faker->image( $dir = public_path( 'uploads/equipments/' ), $width = 640, $height = 480, 'technics', false ),
	];
} );