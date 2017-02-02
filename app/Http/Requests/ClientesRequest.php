<?php

namespace App\Http\Requests;

use App\Cliente;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class ClientesRequest extends Request
{
    private $table = 'clientes';

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
        $Data = Cliente::find($this->cliente);
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
