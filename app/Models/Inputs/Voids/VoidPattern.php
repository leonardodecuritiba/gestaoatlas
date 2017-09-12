<?php

namespace App\Models\Inputs\Voids;

use App\Models\Inputs\Pattern;
use App\Traits\VoidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoidPattern extends Model {
	use SoftDeletes;
	use VoidTrait;
	public $timestamps = true;
	protected $fillable = [
		'security_id',
		'void_id',
		'pattern_id',
		'enabler_id',
		'disabler_id',
		'enabled_at',
		'disabled_at',
	];

	// ************************** RELASHIONSHIP **********************************
	public function pattern() {
		return $this->hasOne( Pattern::class );
	}

}


