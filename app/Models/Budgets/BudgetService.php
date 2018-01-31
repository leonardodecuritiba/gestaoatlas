<?php

namespace App\Models\Budgets;

use App\Servico;
use App\Traits\Relashionship\BudgetInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetService extends Model
{
	use SoftDeletes;
	use BudgetInput;
	public $timestamps = true;
	protected $fillable = [
		'budget_id',
		'service_id',
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

	public function getServiceName()
	{
		return $this->service->getName();
	}

	public function getServiceShortName()
	{
		return $this->service->getShortName();
	}

	public function getOriginalValue()
	{
		return $this->service->valor_float();
	}

	// =====================================================================
	// ======================== RELASHIONSHIP - COUNT ======================
	// =====================================================================


	// =====================================================================
	// ======================== RELASHIONSHIP ==============================
	// =====================================================================

	public function service()
	{
		return $this->belongsTo(Servico::class,'service_id', 'idservico');
	}
}