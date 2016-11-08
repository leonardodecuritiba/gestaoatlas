<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PessoaJuridica extends Model
{
    protected $table = 'pjuridicas';
    protected $primaryKey = 'idpjuridica';
    public $timestamps = true;
    protected $fillable = [
        'cnpj',
        'ie',
        'isencao_ie',
        'razao_social',
        'nome_fantasia',
        'ativ_economica',
        'sit_cad_vigente',
        'sit_cad_status',
        'data_sit_cad',
        'reg_apuracao',
        'data_credenciamento',
        'ind_obrigatoriedade',
        'data_ini_obrigatoriedade'
    ];

    // ************************ FUNCTIONS ******************************
    public function getIeAttribute($value)
    {
        return Controller::mask($value, '###.###.###.###');
    }
    public function getCnpjAttribute($value)
    {
        return Controller::mask($value, '##.###.###/####-##');
    }
    public function getDataSitCadAttribute($value)
    {
        return ($value != NULL)? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):NULL;
    }

    public function getDataCredenciamentoAttribute($value)
    {
        return ($value != NULL)? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):NULL;
    }

    public function getDataIniObrigatoriedadeAttribute($value)
    {
        return ($value != NULL)? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):NULL;
    }

    public function setDataSitCadAttribute($value)
    {
        if($value != NULL && $value != 0){
            $this->attributes['data_sit_cad'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
    }

    public function setDataCredenciamentoAttribute($value)
    {
        if($value != NULL && $value != 0){
            $this->attributes['data_credenciamento'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
    }

    public function setDataIniObrigatoriedadeAttribute($value)
    {
        if ($value != NULL && $value != 0) {
            $this->attributes['data_ini_obrigatoriedade'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
    }




    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }
}
