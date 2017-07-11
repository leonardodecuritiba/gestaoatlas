<?php

namespace App\Http\Requests\Instrumentos;

use App\Http\Requests\Request;
use App\Instrumento;
use Illuminate\Support\Facades\Redirect;

class InstrumentoRequest extends Request
{
    private $table = 'instrumentos';

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
        $Data = Instrumento::find($this->instrumentos);
        $id = count($Data) ? $Data->idinstrumento : 0;
        $idcliente = $this->get('idcliente');
//        dd($idcliente);

        $rules = [
            'idcliente' => 'required|exists:clientes,idcliente',
            'idbase' => 'required|exists:instrumento_bases,id',
            'idsetor' => 'required|exists:instrumento_setors,id',
            'ano' => 'required|digits:4',
        ];
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return array_merge($rules, [
                    'etiqueta_identificacao' => 'required|image',
                    'numero_serie' => 'required|min:1|max:50|
                                            unique_cliente:' . $this->table . ',numero_serie,idcliente,' . $idcliente,
                    'patrimonio' => 'present|min:1|max:50|
                                            unique_cliente:' . $this->table . ',patrimonio,idcliente,' . $idcliente,
                    'inventario' => 'present|min:1|max:50|
                                            unique_cliente:' . $this->table . ',inventario,idcliente,' . $idcliente,
                    'ip' => 'present|ip|
                                            unique_cliente:' . $this->table . ',ip,idcliente,' . $idcliente,
                    'endereco' => 'present|
                                            unique_cliente:' . $this->table . ',endereco,idcliente,' . $idcliente,
                ]);
            }
            case 'PUT':
            case 'PATCH': {
                return array_merge($rules, [
                    'numero_serie' => 'required|min:1|max:50|
                                            unique_cliente:' . $this->table . ',numero_serie,idcliente,' . $idcliente . ',idinstrumento,' . $id,
                    'patrimonio' => 'present|min:1|max:50|
                                            unique_cliente:' . $this->table . ',patrimonio,idcliente,' . $idcliente . ',idinstrumento,' . $id,
                    'inventario' => 'present|min:1|max:50|
                                            unique_cliente:' . $this->table . ',inventario,idcliente,' . $idcliente . ',idinstrumento,' . $id,
                    'ip' => 'present|ip|
                                            unique_cliente:' . $this->table . ',ip,idcliente,' . $idcliente . ',idinstrumento,' . $id,
                    'endereco' => 'present|
                                            unique_cliente:' . $this->table . ',endereco,idcliente,' . $idcliente . ',idinstrumento,' . $id,
                ]);
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
