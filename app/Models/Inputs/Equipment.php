<?php

namespace App\Models\Inputs;

use App\Traits\InstrumentsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model {
	use SoftDeletes;
	use InstrumentsTrait;
	public $timestamps = true;
	protected $table = 'equipments';
	protected $fillable = [
		'idbrand',
		'description',
		'photo',
		'serial_number',
		'model',
	];

	public function getPhoto() {
		return ( $this->attributes['photo'] != '' ) ?
			asset( 'uploads' . DIRECTORY_SEPARATOR . $this->table . DIRECTORY_SEPARATOR . $this->attributes['photo'] )
			: asset( 'imgs' . DIRECTORY_SEPARATOR . 'cogs.png' );
	}

	public function getPhotoThumb() {
		return ( $this->attributes['photo'] != '' ) ?
			asset( 'uploads' . DIRECTORY_SEPARATOR . $this->table . DIRECTORY_SEPARATOR . 'Thumb_' . $this->attributes['photo'] )
			: asset( 'imgs' . DIRECTORY_SEPARATOR . 'cogs.png' );
	}
	// ************************** RELASHIONSHIP **********************************


	// ************************** RELASHIONSHIP **********************************

}
