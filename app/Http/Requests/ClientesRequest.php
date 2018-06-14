<?php

namespace App\Http\Requests;

use App\Cliente;
use App\Helpers\DataHelper;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class ClientesRequest extends Request
{
    private $table = 'clientes';
    private $max_limit_technical;

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
//	    $this->format_inputs();

        $validacao = [
            'centro_custo' => 'required',
            'email_orcamento' => 'required',
            'idsegmento' => 'required',
            'idregiao' => 'required',
            'limite_credito_tecnica' => 'required',
//            'limite_credito_comercial' => 'required|numeric',
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
            'numero_chamado' => 'required',
        ];

        if($this->has('idcliente_centro_custo')){
	        $CentroCusto = Cliente::find($this->get('idcliente_centro_custo'));
	        $this->max_limit_technical = $CentroCusto->getLimitCentroCusto($this->clientes);
	        $validacao['limite_credito_tecnica'] = 'required|numeric|max:' .  $this->max_limit_technical;
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
		        $Data = Cliente::find($this->clientes);
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

	public function format_inputs()
	{
		if($this->has('limite_credito_tecnica')){
			$value = DataHelper::getReal2Float($this->get('limite_credito_tecnica'));
			$this->merge(['limite_credito_tecnica' => $value]);
		}
		if($this->has('limite_credito_comercial')){
			$value = DataHelper::getReal2Float($this->get('limite_credito_comercial'));
			$this->merge(['limite_credito_comercial' => $value]);
		}
	}
	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'limite_credito_tecnica.max'  => 'O Limite de Crédito Técnica não pode ultrapassar ' . DataHelper::getFloat2RealMoeda($this->max_limit_technical),
		];
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
