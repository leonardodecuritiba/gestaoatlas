<?php

namespace App;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PessoaJuridica extends Model
{
    public $timestamps = true;
    protected $table = 'pjuridicas';
    protected $primaryKey = 'idpjuridica';
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

    public function getCnpj()
    {
        return $this->attributes['cnpj'];
    }

    public function getIe()
    {
        return $this->attributes['ie'];
    }
    public function getIeAttribute($value)
    {
        return DataHelper::mask($value, '###.###.###.###');
    }

    public function getCnpjAttribute($value)
    {
        return DataHelper::mask($value, '##.###.###/####-##');
    }

    public function getDataSitCadAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataCredenciamentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataIniObrigatoriedadeAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function setIeAttribute($value)
    {
        $this->attributes['ie'] = DataHelper::getOnlyNumbers($value);
    }

    public function setCnpjAttribute($value)
    {
        $this->attributes['cnpj'] = DataHelper::getOnlyNumbers($value);
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
