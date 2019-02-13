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
        'cest',
        'icms_base_calculo',
        'icms_valor_total',
        'icms_base_calculo_st',
        'icms_valor_total_st',
        'icms_origem',
        'icms_situacao_tributaria',
        'pis_situacao_tributaria',
        'cofins_situacao_tributaria',
        'valor_unitario_comercial',
        'unidade_tributavel',
        'valor_unitario_tributavel',
        'valor_ipi',
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

    public function getCostFormatted()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['custo_final']);
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
        $this->attributes['icms_base_calculo'] = DataHelper::getReal2Float($value);
    }

    public function getIcmsBaseCalculoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsValorTotalAttribute($value)
    {
        $this->attributes['icms_valor_total'] = DataHelper::getReal2Float($value);
    }

    public function getIcmsValorTotalAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setUnidadeTributavelAttribute($value)
    {
        $this->attributes['unidade_tributavel'] = DataHelper::getReal2Float($value);
    }

    public function getValorUnitarioComercialAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorUnitarioComercialAttribute($value)
    {
        $this->attributes['valor_unitario_comercial'] = DataHelper::getReal2Float($value);
    }

    public function getUnidadeTributavelAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsBaseCalculoStAttribute($value)
    {
        $this->attributes['icms_base_calculo_st'] = DataHelper::getReal2Float($value);
    }

    public function getIcmsBaseCalculoStAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setIcmsValorTotalStAttribute($value)
    {
        $this->attributes['icms_valor_total_st'] = DataHelper::getReal2Float($value);
    }

    public function getIcmsValorTotalStAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function setValorUnitarioTributavelAttribute($value)
    {
        $this->attributes['valor_unitario_tributavel'] = DataHelper::getReal2Float($value);
    }

    public function getValorUnitarioTributavelAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorIpiAttribute($value)
    {
        $this->attributes['valor_ipi'] = DataHelper::getReal2Float($value);
    }

    public function valor_ipi_float()
    {
        return $this->attributes['valor_ipi'];
    }

    public function getValorIpiAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorFreteAttribute($value)
    {
        $this->attributes['valor_frete'] = DataHelper::getReal2Float($value);
    }

    public function getValorFreteAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function valor_frete_float()
    {
        return $this->attributes['valor_frete'];
    }

    public function setValorSeguroAttribute($value)
    {
        $this->attributes['valor_seguro'] = DataHelper::getReal2Float($value);
    }

    public function getValorSeguroAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function valor_seguro_float()
    {
        return $this->attributes['valor_seguro'];
    }

    public function setCustoFinalAttribute($value)
    {
        $this->attributes['custo_final'] = DataHelper::getReal2Float($value);
    }

    public function getCustoFinalAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function custo_final_float()
    {
        return $this->attributes['custo_final'];
    }

    public function valor_bruto_float($qtd)
    {
        return $qtd * $this->attributes['custo_final'];
    }

    public function valor_unitario_tributavel_float()
    {
        return $this->attributes['custo_final'];
    }

    public function valor_unitario_comercial_float()
    {
        return $this->attributes['custo_final'];
    }

    public function unidade_tributavel_float()
    {
        return $this->attributes['unidade_tributavel'];
    }

}