<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers\Budget;

use App\Models\Budgets\BudgetService;

class BudgetServiceObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  BudgetService $budget_service
     * @return void
     */
    public function created(BudgetService $budget_service)
    {
    	//update budget total
	    $budget_service->budget->insertInputValues([
	    	'value_total'       => $budget_service->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount

    }
	/**
	 * Listen to the BudgetPart updated event.
	 *
	 * @param  BudgetService $budget_service
	 * @return void
	 */
	public function updated(BudgetService $budget_service)
	{
		$budget_service->budget->insertInputValues([
			'value_total'       => $budget_service->getFinalValue()
		]);
	}

    /**
     * Listen to the User deleting event.
     *
     * @param  BudgetService $budget_service
     * @return void
     */
    public function deleting(BudgetService $budget_service)
    {
	    $budget_service->budget->removeInputValues([
		    'value_total'       => $budget_service->getFinalValue()
	    ]); //getFinalValue (value*quantity) - discount
    }
}