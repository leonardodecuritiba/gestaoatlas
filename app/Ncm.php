<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ncm extends Model
{
    use SoftDeletes;
    protected $table = 'ncm';
    protected $primaryKey = 'idncm';
    public $timestamps = true;
    protected $fillable = [
        'codigo',
        'descricao',
        'aliquota_ipi',
        'aliquota_pis',
        'aliquota_cofins',
        'aliquota_nacional',
        'aliquota_importacao',
    ];

    // ******************** FUNCTIONS ****************************
    // aliquota_ipi (%)
    public function setAliquotaIpiAttribute($value)
    {
        $this->attributes['aliquota_ipi'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaIpiAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    // aliquota_pis (%)
    public function setAliquotaPisAttribute($value)
    {
        $this->attributes['aliquota_pis'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaPisAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    // aliquota_cofins (%)
    public function setAliquotaCofinsAttribute($value)
    {
        $this->attributes['aliquota_cofins'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaCofinsAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    // aliquota_nacional (%)
    public function setAliquotaNacionalAttribute($value)
    {
        $this->attributes['aliquota_nacional'] = floatval(str_replace(',','.',$value));
    }
//    public function getAliquotaNacionalAttribute($value)
//    {
//        return number_format($value,2,',','.');
//    }
    // aliquota_importacao (%)
    public function setAliquotaImportacaoAttribute($value)
    {
        $this->attributes['aliquota_importacao'] = floatval(str_replace(',','.',$value));
    }
//    public function getAliquotaImportacaoAttribute($value)
//    {
//        return number_format($value,2,',','.');
//    }


    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function tributacao()
    {
        return $this->hasOne('App\Tributacao', 'idncm', 'idncm');
    }
    // ********************** BELONGS ********************************
}
