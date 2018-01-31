<?php

namespace App\Models\Budgets;

use App\Peca;
use App\Traits\Relashionship\BudgetInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetPart extends Model
{
	use SoftDeletes;
	use BudgetInput;
	public $timestamps = true;
	protected $fillable = [
		'budget_id',
		'part_id',
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

	public function getPartName()
	{
		return $this->part->getName();
	}

	public function getPartShortName()
	{
		return $this->part->getShortName();
	}

	public function getOriginalValue()
	{
		return $this->part->custo_final;
	}

	// =====================================================================
	// ======================== RELASHIONSHIP - COUNT ======================
	// =====================================================================


	// =====================================================================
	// ======================== RELASHIONSHIP ==============================
	// =====================================================================

	public function part()
	{
		return $this->belongsTo(Peca::class,'part_id', 'idpeca');
	}
}