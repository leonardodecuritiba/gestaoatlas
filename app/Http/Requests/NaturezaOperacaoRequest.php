<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\NaturezaOperacao;
use Illuminate\Support\Facades\Redirect;

class NaturezaOperacaoRequest extends Request
{
    private $table = 'natureza_operacaos';

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
        $Data = NaturezaOperacao::find($this->natureza_operacao);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'numero' => 'required|unique:' . $this->table,
                    'descricao' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'numero' => 'required|unique:' . $this->table . ',numero,' . $id . ',id',
                    'descricao' => 'required',
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
