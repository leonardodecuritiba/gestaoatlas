<?php

namespace App\Models\Inputs\Voids;

use App\Traits\SecurityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voidx extends Model {
	use SoftDeletes;
	use SecurityTrait;
	public $timestamps = true;
	protected $table = 'voids';
	protected $fillable = [
		'security_id',
		'used',
		'number',
	];

	static public function start( $numbers ) {
		foreach ( $numbers as $n ) {
			self::create( [ 'number' => $n ] );
		}

		return;
	}

	static public function setUsed( $id ) {
		return self::find( $id )->update( [ 'used' => 1 ] );
	}

	static public function setUnused( $id ) {
		return self::find( $id )->update( [ 'used' => 0 ] );
	}

	// ************************** SCOPE **********************************

	/**
	 * Scope a query to only include active users.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeUseds( $query ) {
		return $query->where( 'used', 1 );
	}

	/**
	 * Scope a query to only include active users.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeUnuseds( $query ) {
		return $query->where( 'used', 0 );
	}

	// ************************** RELASHIONSHIP **********************************

	public function void_tool() {
		return $this->hasOne( VoidTool::class, 'void_id' );
	}

	public function void_pattern() {
		return $this->hasOne( VoidPattern::class, 'void_id' );
	}

}


