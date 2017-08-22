<?php

namespace App\Http\Requests\Inputs;

use App\Http\Requests\Request;
use App\Models\Inputs\Equipment;
use Illuminate\Support\Facades\Redirect;

class EquipmentRequest extends Request {
	private $table = 'equipments';

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
		$Data = Equipment::find( $this->equipments );
		$id   = count( $Data ) ? $Data->id : 0;
		switch ( $this->method() ) {
			case 'GET':
			case 'DELETE': {
				return [];
			}
			case 'POST': {
				return [
					'idbrand'       => 'required|exists:marcas,idmarca',
					'description'   => 'required|min:1|max:100|unique:' . $this->table,
					'photo'         => 'required|image',
					'serial_number' => 'required|min:1|max:50',
					'model'         => 'required|min:1|max:100',
				];
			}
			case 'PUT':
			case 'PATCH': {
				return [
					'idbrand'       => 'required|exists:marcas,idmarca',
					'description'   => 'required|min:1|max:50|unique:' . $this->table . ',description,' . $id . ',id',
//				    'photo' => 'required|image',
					'serial_number' => 'required|min:1|max:50',
					'model'         => 'required|min:1|max:100',
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
