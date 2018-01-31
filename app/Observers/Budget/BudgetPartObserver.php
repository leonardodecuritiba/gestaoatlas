<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers\Budget;

use App\Models\Budgets\BudgetPart;

class BudgetPartObserver
{
    /**
     * Listen to the BudgetPart created event.
     *
     * @param  BudgetPart $budget_part
     * @return void
     */
    public function created(BudgetPart $budget_part)
    {
    	//update budget total
	    $budget_part->budget->insertInputValues([
	    	'value_total'       => $budget_part->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount
    }
    /**
     * Listen to the BudgetPart updated event.
     *
     * @param  BudgetPart $budget_part
     * @return void
     */
    public function updated(BudgetPart $budget_part)
    {
	    $budget_part->budget->insertInputValues([
	    	'value_total'       => $budget_part->getFinalValue()
	    ]);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  BudgetPart $budget_part
     * @return void
     */
    public function deleting(BudgetPart $budget_part)
    {
	    $budget_part->budget->removeInputValues([
		    'value_total'       => $budget_part->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount
    }
}