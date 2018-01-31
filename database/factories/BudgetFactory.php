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

use App\Cliente;
use App\Peca;
use App\Servico;
use App\Kit;
use App\Colaborador;
use App\Models\Budgets\Budget;
use App\Models\Budgets\BudgetService;
use App\Models\Budgets\BudgetPart;
use App\Models\Budgets\BudgetKit;

// Budget
$factory->define(Budget::class, function (Faker\Generator $faker) {
	$client = Cliente::all()->random(1);
	$collaborator = Colaborador::all()->random(1)->first();
	$cost_displacement = $client->getCostDisplacement();
	$cost_toll = $client->getCostToll();
	$cost_other = $client->getCostOther();

	$discount = $faker->boolean;
	$discount_master = $discount ? $faker->randomFloat( 0, 0,5) : 0;
	$discount_technician = $discount ? $faker->randomFloat( 0, 0,5) : 0;
	$increase_technician = $discount ? $faker->randomFloat( 0, 0,5) : 0;
	return [
        'client_id'             => $client->idcliente,
        'collaborator_id'       => $collaborator->idcolaborador,
        'situation_id'          => $faker->numberBetween( 1, 6 ),
        'responsible'           => $faker->name,
        'responsible_cpf'       => $faker->cpf( false ),
        'responsible_office'    => $faker->word,
        'value_total'           => 0,
        'value_end'             => 0,
        'discount_master'       => $discount_master,
        'discount_technician'   => $discount_technician,
        'increase_technician'   => $increase_technician,
        'cost_displacement'     => $cost_displacement,
        'cost_toll'             => $cost_toll,
        'cost_other'            => $cost_other,
        'cost_exemption'        => 0,
    ];
});

// BudgetPart
$factory->define(BudgetPart::class, function (Faker\Generator $faker) {
	$budget = Budget::all()->random(1);
	$part = Peca::all()->random(1);
	$value = $part->getCost();
	$value = 100;
	$quantity = $faker->numberBetween( 1, 3 );
	$discount = $faker->boolean ? $faker->randomFloat( 0, 0,5) : 0;
	return [
		'budget_id' => $budget->id,
		'part_id'   => $part->idpeca,
		'value'     => $value,
		'quantity'  => $quantity,
		'discount'  => $discount,
	];
});

// BudgetService
$factory->define(BudgetService::class, function (Faker\Generator $faker) {
	$budget = Budget::all()->random(1);
	$service = Servico::all()->random(1);
	$value = $service->getCost();
	$value = 100;
	$quantity = $faker->numberBetween( 1, 3 );
	$discount = $faker->boolean ? $faker->randomFloat( 0, 0,5) : 0;
	return [
		'budget_id' => $budget->id,
		'service_id'=> $service->idservico,
		'value'     => $value,
		'quantity'  => $quantity,
		'discount'  => $discount,
	];
});

// BudgetKit
$factory->define(BudgetKit::class, function (Faker\Generator $faker) {
	$budget = Budget::all()->random(1);
	$kit = Kit::all()->random(1);
	$value = $kit->getCost();
	$quantity = $faker->numberBetween( 1, 3 );
	$discount = $faker->boolean ? $faker->randomFloat( 0, 0,5) : 0;
	return [
		'budget_id' => $budget->id,
		'kit_id'    => $kit->idkit,
		'value'     => $value,
		'quantity'  => $quantity,
		'discount'  => $discount,
	];
});