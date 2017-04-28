<?php

namespace App\Http\Requests\Instrumentos;

use App\Models\Instrumentos\InstrumentoBase;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class InstrumentoBaseRequest extends Request
{
    private $table = 'instrumento_bases';

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
        $Data = InstrumentoBase::find($this->instrumento_bases);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'idmodelo' => 'required|exists:instrumento_modelos,id',
                    'descricao' => 'required|min:3|max:100|unique:' . $this->table,
                    'divisao' => 'required|min:1|max:100',
                    'portaria' => 'required|min:1|max:100',
                    'capacidade' => 'required|min:1|max:100'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'idmodelo' => 'required|exists:instrumento_modelos,id',
                    'descricao' => 'required|min:3|max:100|unique:' . $this->table . ',descricao,' . $id . ',id',
                    'divisao' => 'required|min:1|max:100',
                    'portaria' => 'required|min:1|max:100',
                    'capacidade' => 'required|min:1|max:100'
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
