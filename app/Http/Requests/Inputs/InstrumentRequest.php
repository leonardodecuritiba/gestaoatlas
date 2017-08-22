<?php

namespace App\Http\Requests\Inputs;

use App\Http\Requests\Request;
use App\Models\Inputs\Instrument;
use Illuminate\Support\Facades\Redirect;

class InstrumentRequest extends Request {
	private $table = 'instruments';

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$Data = Instrument::find( $this->instruments );
		$id   = count( $Data ) ? $Data->id : 0;
		switch ( $this->method() ) {
			case 'GET':
			case 'DELETE': {
				return [];
			}
			case 'POST': {
				return [
					'serial_number' => 'required|min:1|max:50|unique:' . $this->table,
					'inventory'     => 'required|min:1|max:50|unique:' . $this->table,
					'idbase'        => 'required|exists:instrumento_bases,id',
					'year'          => 'required|digits:4'
				];
			}
			case 'PUT':
			case 'PATCH': {
				return [
					'serial_number' => 'required|min:1|max:50|unique:' . $this->table . ',serial_number,' . $id . ',id',
					'inventory'     => 'required|min:1|max:50|unique:' . $this->table . ',inventory,' . $id . ',id',
					'idbase'        => 'required|exists:instrumento_bases,id',
					'year'          => 'required|digits:4'
				];
			}
			default:
				break;
		}
	}

	/**
	 * Get the response that handle the request errors.
	 *
	 * @param  array $errors
	 *
	 * @return array
	 */
	public function response( array $errors ) {
		return Redirect::back()->withErrors( $errors )->withInput();
	}
}
