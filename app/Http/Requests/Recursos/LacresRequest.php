<?php

namespace App\Http\Requests\Recursos;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Redirect;

class LacresRequest extends Request
{

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
        $numeracao_inicial = $this->get('numeracao_inicial');
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'numeracao_inicial' => 'required_with:numeracao_final|integer|min:1',
                    'numeracao_final' => 'required_with:numeracao_inicial|integer|min:' . ($numeracao_inicial + 1)
                ];
            }
            case 'PUT':
            case 'PATCH': {
                [
                    'numeracao_inicial' => 'required_with:numeracao_final|integer|min:1',
                    'numeracao_final' => 'required_with:numeracao_inicial|integer|min:' . ($numeracao_inicial + 1)
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
