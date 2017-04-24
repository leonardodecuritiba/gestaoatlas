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
        $Data = Cliente::find($this->clientes);
        $id = count($Data) ? $Data->id : 0;

        $validacao = [
            'centro_custo' => 'required',
            'email_orcamento' => 'required',
            'idsegmento' => 'required',
            'idregiao' => 'required',
//            'distancia' => 'required',
//            'pedagios' => 'required',

            'idtabela_preco_tecnica' => 'required',
            'idforma_pagamento_tecnica' => 'required',
            'idemissao_tecnica' => 'required',
            'prazo_pagamento_tecnica' => 'required',

            'idtabela_preco_comercial' => 'required',
            'idforma_pagamento_comercial' => 'required',
            'idemissao_comercial' => 'required',
            'prazo_pagamento_comercial' => 'required',
        ];
        if ($this->get('centro_custo') == '0') {
            $validacao = array_merge($validacao, [
                'limite_credito_tecnica' => 'required'
            ]);
        }

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                if ($this->get('tipo_cliente') == "0") {
                    //pessoa física
                    $validacao = array_merge($validacao, [
                        'cpf' => 'unique:pfisicas',
                    ]);
                } else {
                    //pessoa juridica, teste da isenção IE
                    if ($this->has('isencao_ie')) {
                        $data['isencao_ie'] = 1;
                        $validacao = array_merge($validacao, [
                            'cnpj' => 'unique:pjuridicas',
                        ]);
                    } else {
                        $validacao = array_merge($validacao, [
                            'cnpj' => 'unique:pjuridicas',
                            'ie' => 'unique:pjuridicas',
                        ]);
                    }
                    $validacao = array_merge($validacao, [
                        'razao_social' => 'required',
                        'nome_fantasia' => 'required',
                    ]);
                }
                return $validacao;
            }
            case 'PUT':
            case 'PATCH': {
                if ($Data->getType()->tipo_cliente == 0) {
                    //pessoa física
                    $idpfisica = $Data->pessoa_fisica->idpfisica;
                    $validacao = array_merge($validacao, [
                        'cpf' => 'unique:pfisicas,cpf,' . $idpfisica . ',idpfisica',
                    ]);
                } else {
                    //pessoa juridica, teste da isenção IE
                    $idpjuridica = $Data->pessoa_juridica->idpjuridica;
                    if ($this->has('isencao_ie')) {
                        $data['isencao_ie'] = 1;
                        $validacao = array_merge($validacao, [
                            'cnpj' => 'unique:pjuridicas,cnpj,' . $idpjuridica . ',idpjuridica'
                        ]);
                    } else {
                        $validacao = array_merge($validacao, [
                            'cnpj' => 'unique:pjuridicas,cnpj,' . $idpjuridica . ',idpjuridica',
                            'ie' => 'unique:pjuridicas,ie,' . $idpjuridica . ',idpjuridica',
                        ]);
                    }
                    $validacao = array_merge($validacao, [
                        'razao_social' => 'required',
                        'nome_fantasia' => 'required',
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
