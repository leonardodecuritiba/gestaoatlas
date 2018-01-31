<?php

namespace App\Traits\Relashionship;

use App\Helpers\DataHelper;
use App\Models\Budgets\Budget;

trait BudgetInput {

	public function getOriginalValueFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getOriginalValue());
	}

	public function getFinalValueFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getFinalValue());
	}

	public function getFinalValue()
	{
		return ($this->getTotalValue() - $this->getDiscountValue());
	}

	public function getTotalValueFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getTotalValue());
	}

	public function getTotalValue()
	{
		return ($this->attributes['value'] * $this->attributes['quantity']);
	}

	public function getValueFormatted()
	{
		return DataHelper::getFloat2RealMoeda( $this->attributes['value']);
	}

	public function getQuantityFormatted()
	{
		return DataHelper::getFloat2RealMoeda( $this->attributes['quantity']);
	}

	public function getDiscountValue()
	{
		return $this->attributes['discount'] > 0 ? $this->attributes['discount'] : 0;
	}

	public function getDiscountFormatted()
	{
		return DataHelper::getFloat2RealMoeda( $this->getDiscountValue());
	}

	public function budget()
	{
		return $this->belongsTo(Budget::class, 'budget_id');
	}


}