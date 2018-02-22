<?php

namespace App\Models\Budgets;

use App\Helpers\DataHelper;
use App\Peca;
use App\Traits\DateTimeTrait;
use App\Traits\Relashionship\ClientTrait;
use App\Traits\Relashionship\CollaboratorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
	use SoftDeletes;
	use ClientTrait;
	use CollaboratorTrait;
	use DateTimeTrait;
	public $timestamps = true;
	protected $fillable = [
		'client_id',
		'collaborator_id',
		'situation_id',
		'responsible',
		'responsible_cpf',
		'responsible_office',

		'value_total',
		'value_end',

		'discount_master', // desconto concedido pelo admin sobre value_total (em porcentagem)
		'discount_technician', // desconto concedido pelo técnico sobre value_total (em porcentagem)
		'increase_technician', // acrescimo concedido pelo técnico sobre value_total (em porcentagem)

		'cost_displacement',
		'cost_toll',
		'cost_other',
		'cost_exemption',

		'closed_at'
	];

	static public $_SITUATION_OPPENED_ = 0;
	static public $_SITUATION_CLOSED_ = 1;

	// =====================================================================
	// ======================== FILTERS ====================================
	// =====================================================================


	static public function filterById($id)
	{
		return self::where('id', $id)
		                  ->with('client', 'collaborator')
		                  ->get();
	}

	static public function filter(array $data)
	{
		$query = (new self)->newQuery();
		if(isset($data['situation']) && ($data['situation'] != "")){
			$query->where('situation_id', $data['situation']);
		}
		if (isset($data['date'])) {
			$query->where( 'created_at', '>=', DataHelper::getPrettyToCarbonZero( $data['date'] ) );
		}
		if (isset($data['client_id']) && ($data['client_id'] != "")) {
			$query->where('client_id', $data['client_id']);
		}
		$User = Auth::user();
		if ($User->hasRole('tecnico')) {
			$query->where('collaborator_id', $User->colaborador->idcolaborador);
		}
		return $query
			->with('client', 'collaborator');
	}

	// =====================================================================
	// ======================== FUNCTIONS ==================================
	// =====================================================================

	public function reopen()
	{
		return $this->update([
			'closed_at'     => NULL,
			'situation_id'  => self::$_SITUATION_OPPENED_,
		]);
	}

	public function close(array $data)
	{
		if(isset($data['cost_exemption'])){
			$this->exemptCost($data['cost_exemption']);
		}
		$data['closed_at'] = Carbon::now();
		$data['situation_id'] = self::$_SITUATION_CLOSED_;
		$this->fill($data);
		return $this->save();
	}

	public function exemptCost($exemption)
	{
		$this->attributes['cost_exemption'] = $exemption;
		if($exemption){ //zero
			$this->attributes['cost_displacement']  = 0;
			$this->attributes['cost_toll']          = 0;
			$this->attributes['cost_other']         = 0;
		} else { //get cost from client
			$client = $this->client;
			$this->attributes['cost_displacement']  = $client->getCostDisplacement();
			$this->attributes['cost_toll']          = $client->getCostToll();
			$this->attributes['cost_other']         = $client->getCostOther();
		}
	}


	public function addPart(array $data)
	{
		$data['budget_id'] = $this->attributes['id'];
		return BudgetPart::create($data);
	}

	public function addService(array $data)
	{
		$data['budget_id'] = $this->attributes['id'];
		return BudgetService::create($data);
	}

	public function getPartsFormatted()
	{
		return $this->parts->map( function ( $s ) {
			return [
				'id'                => $s->id,
				'name'              => $s->getPartName(),
				'price'             => $s->getValueFormatted(),
				'quantity'          => $s->quantity,
				'discount'          => $s->getDiscountFormatted(),
				'total'             => $s->getFinalValueFormatted(),
//				'created_at'        => $s->getCreatedAtFormatted(),
//				'created_at_time'   => $s->getCreatedAtTime()
			];
		} );
	}

	public function getServicesFormatted()
	{
		return $this->services->map( function ( $s ) {
			return [
				'id'                => $s->id,
				'name'              => $s->getServiceName(),
				'price'             => $s->getValueFormatted(),
				'quantity'          => $s->quantity,
				'discount'          => $s->getDiscountFormatted(),
				'total'             => $s->getFinalValueFormatted(),
//				'created_at'        => $s->getCreatedAtFormatted(),
//				'created_at_time'   => $s->getCreatedAtTime()
			];
		} );
	}

	// =====================================================================
	// ======================== SITUATION ==================================
	// =====================================================================

	public function getSituationColor()
	{
		switch ($this->attributes['situation_id']){
			case self::$_SITUATION_OPPENED_:
				return 'warning';
			case self::$_SITUATION_CLOSED_:
				return 'danger';
		}
	}

	public function getSituationText()
	{
		switch ($this->attributes['situation_id']){
			case self::$_SITUATION_OPPENED_:
				return 'Aberto';
			case self::$_SITUATION_CLOSED_:
                return 'Arquivado';
		}
	}

	public function isClosed()
	{
		return ($this->attributes['situation_id'] == self::$_SITUATION_CLOSED_);
	}

	public function getShowUrl()
	{
		return route(($this->isClosed() ? 'budgets.summary' : 'budgets.show'), $this->getAttribute('id'));
	}

	// =====================================================================
	// ======================== MAPS =======================================
	// =====================================================================

	public function getMapList() {
		return [
			'entity'          => 'sensors',
			'id'              => $this->getAttribute('id'),
			'name'            => $this->getShortName(),
			'sensor_type'     => $this->getShortSensorTypeName(),
			'author'          => $this->getShortAuthorName(),
			'n_alerts'        => $this->getAlertsCount(),
			'n_reports'       => $this->getReportsCount(),
			'created_at'      => $this->getCreatedAtFormatted(),
			'created_at_time' => $this->getCreatedAtTime(),
			'active'          => $this->getActiveFullResponse()
		];
	}

	public function getMaptoSelectList() {
		return [
			'id'          => $this->getAttribute('id'),
			'description' => $this->getShortName()
		];
	}

	static public function getAlltoSelectList()
	{
		return self::get()->map(function ($s) {
			return [
				'id' => $s->id,
				'description' => $s->getShortName()
			];
		})->pluck('description', 'id');
	}


	// =====================================================================
	// ======================== GETTERS ====================================
	// =====================================================================

	public function getResponsibleCpf()
	{
		return DataHelper::mask($this->attributes['responsible_cpf'],'###.###.###-##');
	}


	// ======================== VALUES =====================================

	public function getValueTotal()
	{
		return $this->attributes['value_total'];
	}

	public function getValueTotalFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getValueTotal());
	}

	public function getValueEnd()
	{
		return $this->attributes['value_end'];
	}

	public function getValueEndFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getValueEnd());
	}




	public function getDiscountTotal()
	{
		return $this->attributes['value_total'] * (($this->attributes['discount_master'] + $this->attributes['discount_technician'])/100);
	} //in percents

	public function getDiscountTotalFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getDiscountTotal());
	}

	public function getDiscountMaster()
	{
		return $this->attributes['value_total'] * ($this->attributes['discount_master']/100);
	}

	public function getDiscountMasterFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getDiscountMaster());
	}

	public function getDiscountTechnician() //in real
	{
		return $this->attributes['value_total'] * ($this->attributes['discount_technician']/100);
	}

	public function getDiscountTechnicianFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getDiscountTechnician());
	}

	public function getIncreaseTechnician()
	{
		return $this->attributes['value_total'] * ($this->attributes['increase_technician']/100);
	} //in real

	public function getIncreaseTechnicianFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getIncreaseTechnician());
	}



	public function getCostDisplacement()
	{
		return $this->attributes['cost_displacement'];
	}

	public function getCostDisplacementFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getCostDisplacement());
	}

	public function getCostToll()
	{
		return $this->attributes['cost_toll'];
	}

	public function getCostTollFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getCostToll());
	}

	public function getCostOther()
	{
		return $this->attributes['cost_other'];
	}

	public function getCostOtherFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getCostOther());
	}

	public function getCostTotal()
	{
		return $this->getCostDisplacement() + $this->getCostToll() + $this->getCostOther();
	}

	public function getCostTotalFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getCostTotal());
	}


	// =====================================================================
	// ======================== SETTERS ====================================
	// =====================================================================


	public function insertInputValues(array $array)
	{
		$this->value_total += $array['value_total'];
		$this->value_end = $this->attributes['value_total']
		                   + $this->getCostTotal()
		                   + $this->getIncreaseTechnician()
		                   - $this->getDiscountTotal();
		return $this->save();
	}

	public function removeInputValues(array $array)
	{
		$this->value_total -= $array['value_total'];
		$this->value_end = $this->attributes['value_total']
		                   + $this->getCostTotal()
		                   + $this->getIncreaseTechnician()
		                   - $this->getDiscountTotal();
		return $this->save();
	}

	// =====================================================================
	// ======================== RELASHIONSHIP - GETTERS ====================
	// =====================================================================


//	public function getRange()
//	{
//		return $this->sensor_type->getRange();
//	}

	// =====================================================================
	// ======================== RELASHIONSHIP - COUNT ======================
	// =====================================================================

//	public function getAlertsCount()
//	{
//		return $this->alerts->count();
//	}

	// =====================================================================
	// ======================== RELASHIONSHIP ==============================
	// =====================================================================


	public function getServiceById($id)
	{
		return $this->parts()->where('part_id', $id)->first();
	}

	public function getPartById($id)
	{
		return $this->services()->where('service_id', $id)->first();
	}

	public function parts()
	{
		return $this->hasMany(BudgetPart::class,'budget_id');
	}

	public function services()
	{
		return $this->hasMany(BudgetService::class,'budget_id');
	}
}