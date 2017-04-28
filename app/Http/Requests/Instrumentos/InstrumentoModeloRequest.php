<?php

namespace App\Http\Requests\Instrumentos;

use App\Models\Instrumentos\InstrumentoModelo;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class InstrumentoModeloRequest extends Request
{
    private $table = 'instrumento_modelos';

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
        $Data = InstrumentoModelo::find($this->instrumento_modelos);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'idinstrumento_marca' => 'required|exists:instrumento_marcas,id',
                    'descricao' => 'required|min:3|max:100|unique:' . $this->table,
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'idinstrumento_marca' => 'required|exists:instrumento_marcas,id',
                    'descricao' => 'required|min:3|max:100|unique:' . $this->table . ',descricao,' . $id . ',id',
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
