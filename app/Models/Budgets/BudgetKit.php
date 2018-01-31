<?php

namespace App\Models\Budgets;

use App\Kit;
use App\Traits\Relashionship\BudgetInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetKit extends Model
{
	use SoftDeletes;
	use BudgetInput;
	public $timestamps = true;
	protected $fillable = [
		'budget_id',
		'kit_id',
		'value',
		'quantity',
		'discount'
	];

	// =====================================================================
	// ======================== MAPS =======================================
	// =====================================================================

	// =====================================================================
	// ======================== GETTERS ====================================
	// =====================================================================

	// =====================================================================
	// ======================== RELASHIONSHIP - GETTERS ====================
	// =====================================================================

	public function getKitName()
	{
		return $this->kit->getName();
	}

	public function getKitShortName()
	{
		return $this->kit->getShortName();
	}

	public function getOriginalValue()
	{
		return $this->kit->valor_float();
	}

	// =====================================================================
	// ======================== RELASHIONSHIP - COUNT ======================
	// =====================================================================


	// =====================================================================
	// ======================== RELASHIONSHIP ==============================
	// =====================================================================

	public function kit()
	{
		return $this->belongsTo(Kit::class,'kit_id', 'idkit');
	}
}