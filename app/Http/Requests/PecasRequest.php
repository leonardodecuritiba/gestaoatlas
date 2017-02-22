<?php

namespace App\Http\Requests;

use App\Peca;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class PecasRequest extends Request
{
    private $table = 'pecas';

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
        $Data = Peca::find($this->peca);
        $id = count($Data) ? $Data->id : 0;
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'PUT':
            case 'PATCH':
            case 'POST': {
                return [
                    'idfornecedor' => 'required|exists:fornecedores',
                    'idmarca' => 'required|exists:marcas',
                    'idgrupo' => 'required|exists:grupos',
                    'idunidade' => 'required|exists:unidades',
                    'tipo' => 'required',
                    'descricao' => 'required',
                    'descricao_tecnico' => 'required',
                    'garantia' => 'required',
                    'comissao_tecnico' => 'required',
                    'comissao_vendedor' => 'required',

                    //peca_tributacao
                    'idcfop' => 'required|exists:cfops,id',
                    'idcst' => 'required|exists:csts,id',
                    'idnatureza_operacao' => 'required|exists:natureza_operacaos,id',
                    'idncm' => 'required|exists:ncm',
//                    'icms_base_calculo' => 'required',
//                    'icms_valor_total' => 'required',
//                    'icms_base_calculo_st' => 'required',
//                    'icms_valor_total_st' => 'required',
//                    'valor_ipi' => 'required',
//                    'valor_unitario_tributavel' => 'required',
//                    'icms_situacao_tributaria' => 'required',
//                    'icms_origem' => 'required',
//                    'pis_situacao_tributaria' => 'required',
//                    'valor_frete' => 'required',
//                    'valor_seguro' => 'required',
                    'custo_final' => 'required',
                    'cest' => 'required',

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
