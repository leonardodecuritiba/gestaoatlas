<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers\Budget;

use App\Models\Budgets\BudgetKit;

class BudgetKitObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  BudgetKit $budget_kit
     * @return void
     */
    public function created(BudgetKit $budget_kit)
    {
    	//update budget total
	    $budget_kit->budget->insertInputValues([
	    	'value_total'       => $budget_kit->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount

    }

    /**
     * Listen to the User deleting event.
     *
     * @param  BudgetKit $budget_kit
     * @return void
     */
    public function deleting(BudgetKit $budget_kit)
    {
	    $budget_kit->budget->removeInputValues([
		    'value_total'       => $budget_kit->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount
    }
}