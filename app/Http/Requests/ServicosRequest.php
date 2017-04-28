<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Servico;

class ServicosRequest extends Request
{
    private $table = 'servicos';

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
        $Data = Servico::find($this->servico);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'idgrupo' => 'required|exists:grupos',
                    'idunidade' => 'required|exists:unidades',
                    'nome' => 'required|unique:' . $this->table,
                    'descricao' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'idgrupo' => 'required|exists:grupos',
                    'idunidade' => 'required|exists:unidades',
                    'nome' => 'unique:' . $this->table . ',nome,' . $id . ',idservico',
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
