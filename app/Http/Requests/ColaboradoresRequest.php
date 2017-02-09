<?php

namespace App\Http\Requests;

use App\Colaborador;
use App\Http\Requests\Request;
use App\Role;
use Illuminate\Support\Facades\Redirect;

class ColaboradoresRequest extends Request
{
    private $table = 'colaboradores';

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
        $Data = Colaborador::find($this->colaborador);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                $role = Role::find($this->tipo_cadastro);
                $validacao = [
                    'email' => 'email',
                    'nome' => 'required',
                    'cpf' => 'unique:' . $this->table,
                    'rg' => 'unique:' . $this->table,
                    'data_nascimento' => 'required',
                    'cnh' => 'image',
                    'carteira_trabalho' => 'image',
                ];
                if ($role->name == 'tecnico') {
                    $validacao = array_merge($validacao, [
                        'carteira_imetro' => 'image',
                        'carteira_ipem' => 'image',
                    ]);
                }
                return $validacao;
            }
            case 'PUT':
            case 'PATCH': {
                $validacao = [
                    'email' => 'email',
                    'nome' => 'required',
                    'cpf' => 'unique:colaboradores,cpf,' . $id . ',idcolaborador',
                    'rg' => 'unique:colaboradores,rg,' . $id . ',idcolaborador',
                    'data_nascimento' => 'required',
                    'cnh' => 'image',
                    'carteira_trabalho' => 'image',
                ];
                if ($Data->hasRole('tecnico')) {
                    $validacao = array_merge($validacao, [
                        'carteira_imetro' => 'image',
                        'carteira_ipem' => 'image',
                    ]);
                }
                return $validacao;
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
