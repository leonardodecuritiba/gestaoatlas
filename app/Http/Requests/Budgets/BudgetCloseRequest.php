<?php

namespace App\Http\Requests\Budgets;

use App\Http\Requests\Request;
use App\Models\Inputs\Equipment;
use Illuminate\Support\Facades\Redirect;

class BudgetCloseRequest extends Request {
	private $table = 'budgets';

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
		$this->transform();
		return [
			'responsible'       => 'required|min:1|max:100',
			'responsible_cpf'   => 'required|min:1|max:16',
			'responsible_office'=> 'required|min:1|max:50',
		];
	}

	/**
	 * Get the response that handle the request errors.
	 *
	 * @param  array $errors
	 *
	 * @return array
	 */
	public function response( array $errors )
	{
		return Redirect::back()->withErrors( $errors )->withInput();
	}

	public function transform()
	{
		if($this->has('cost_exemption')){
			$this->merge(['cost_exemption'=>1]);
		} else {
			$this->merge(['cost_exemption'=>0]);
		}
	}
}
