<?php

namespace App\Http\Requests;

use App\Cfop;
use App\Http\Requests\Request;

class CfopRequest extends Request
{
    private $table = 'cfops';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $Data = Cfop::find($this->cfop);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'numeracao' => 'required|exists:' . $this->table . ',id',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'numeracao' => 'required|unique:' . $this->table . ',numeracao,' . $id . ',id',
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
     * @return array
     */
    public function response(array $errors)
    {
        return Redirect::back()->withErrors($errors)->withInput();
    }
}
