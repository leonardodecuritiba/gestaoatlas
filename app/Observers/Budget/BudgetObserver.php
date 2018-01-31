<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers\Budget;

use App\Models\Budgets\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetObserver
{
    /**
     * Listen to the User create event.
     *
     * @param  Budget $budget
     * @return void
     */
    public function creating(Budget $budget)
    {
    	//update budget total
	    $client = $budget->client;
	    $budget->cost_displacement  = $client->getCostDisplacement();
	    $budget->cost_toll          = $client->getCostToll();
	    $budget->cost_other         = $client->getCostOther();
	    $budget->collaborator_id    = Auth::user()->colaborador->idcolaborador;
	    $budget->situation_id       = $budget::$_SITUATION_OPPENED_;
//	    $budget->save();
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Budget $budget
     * @return void
     */
    public function deleting(Budget $budget)
    {

    }
}