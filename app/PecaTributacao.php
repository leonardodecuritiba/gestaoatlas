<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecaTributacao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'idcfop',
        'idcst',
        'idnatureza_operacao',
        'idncm',
        'icms_base_calculo',
        'icms_valor_total',
        'icms_base_calculo_st',
        'icms_valor_total_st',
        'valor_ipi',
        'valor_unitario_tributavel',
        'icms_situacao_tributaria',
        'icms_origem',
        'pis_situacao_tributaria',
        'valor_frete',
        'valor_seguro',
        'custo_final',
    ];


    // ******************** RELASHIONSHIP ******************************
    // ************************** belongsTo ****************************
    public function cfop()
    {
        return $this->belongsTo('App\Cfop', 'idcfop');
    }

    public function cst()
    {
        return $this->belongsTo('App\Cst', 'idcst');
    }

    public function ncm()
    {
        return $this->belongsTo('App\Ncm', 'idncm');
    }

    public function natureza_operacao()
    {
        return $this->belongsTo('App\NaturezaOperacao', 'idnatureza_operacao');
    }

    public function peca()
    {
        return $this->hasOne('App\Peca', 'idpeca_tributacao');
    }


    public function setIcmsBaseCalculoAttribute($value)
    {
        $this->attributes['icms_base_calculo'] = DataHelper::getPercent2Float($value);
    }

    public function getIcmsBaseCalculoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorTotalAttribute($value)
    {
        $this->attributes['icms_valor_total'] = DataHelper::getPercent2Float($value);
    }

    public function getValorTotalAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsBaseCalculoStAttribute($value)
    {
        $this->attributes['icms_base_calculo_st'] = DataHelper::getPercent2Float($value);
    }

    public function getIcmsBaseCalculoStAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsValorTotalStAttribute($value)
    {
        $this->attributes['icms_valor_total_st'] = DataHelper::getPercent2Float($value);
    }

    public function getIcmsValorTotalStAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorIpiAttribute($value)
    {
        $this->attributes['valor_ipi'] = DataHelper::getPercent2Float($value);
    }

    public function getValorIpiAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorUnitarioTributavelAttribute($value)
    {
        $this->attributes['valor_unitario_tributavel'] = DataHelper::getPercent2Float($value);
    }

    public function getValorUnitarioTributavelAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsSituacaoTributariaAttribute($value)
    {
        $this->attributes['icms_situacao_tributaria'] = DataHelper::getPercent2Float($value);
    }

    public function getIcmsSituacaoTributariaAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsOrigemAttribute($value)
    {
        $this->attributes['icms_origem'] = DataHelper::getPercent2Float($value);
    }

    public function getIcmsOrigemAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setPisSituacaoTributariaAttribute($value)
    {
        $this->attributes['pis_situacao_tributaria'] = DataHelper::getPercent2Float($value);
    }

    public function getPisSituacaoTributariaAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorFreteAttribute($value)
    {
        $this->attributes['valor_frete'] = DataHelper::getPercent2Float($value);
    }

    public function getValorFreteAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorSeguroAttribute($value)
    {
        $this->attributes['valor_seguro'] = DataHelper::getPercent2Float($value);
    }

    public function getValorSeguroAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setCustoFinalAttribute($value)
    {
        $this->attributes['custo_final'] = DataHelper::getPercent2Float($value);
    }

    public function getCustoFinalAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
}