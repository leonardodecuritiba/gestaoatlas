<?php

namespace App\Http\Requests\Instrumentos;

use App\Models\Instrumentos\InstrumentoSetor;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class InstrumentoSetorRequest extends Request
{
    private $table = 'instrumento_setors';

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
        $Data = InstrumentoSetor::find($this->instrumento_setors);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'descricao' => 'required|min:3|max:100|unique:' . $this->table,
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
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
