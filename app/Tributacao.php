<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tributacao extends Model
{
    use SoftDeletes;
    protected $table = 'tributacao';
    protected $primaryKey = 'idtributacao';
    public $timestamps = true;
    protected $fillable = [
        'idncm',
        'idcategoria_tributacao',
        'idorigem_tributacao',
        'idcst_ipi',
        'peso_liquido',
        'peso_bruto',
        'ipi',
        'isencao_icms',
        'ipi_venda',
        'reducao_icms',
        'icms',
        'reducao_bc_icms',
        'reducao_bc_icms_st',
        'aliquota_icms',
        'aliquota_ii',
        'icms_importacao',
        'aliquota_cofins_importacao',
        'aliquota_pis_importacao'
    ];

    // ******************** FUNCTIONS ****************************
    //Peso
    public function setPesoLiquidoAttribute($value)
    {
        $this->attributes['peso_liquido'] = floatval(str_replace(',','.',$value));
    }
    public function getPesoLiquidoAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function setPesoBrutoAttribute($value)
    {
        $this->attributes['peso_bruto'] = floatval(str_replace(',','.',$value));
    }
    public function getPesoBrutoAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    // (%)
    public function setIpiAttribute($value)
    {
        $this->attributes['ipi'] = floatval(str_replace(',','.',$value));
    }
    public function getIpiAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function setReducaoBcIcmsAttribute($value)
    {
        $this->attributes['reducao_bc_icms'] = floatval(str_replace(',','.',$value));
    }
    public function getReducaoBcIcmsAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function setReducaoBcIcmsStAttribute($value)
    {
        $this->attributes['reducao_bc_icms_st'] = floatval(str_replace(',','.',$value));
    }
    public function getReducaoBcIcmsStAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setAliquotaIcmsAttribute($value)
    {
        $this->attributes['aliquota_icms'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaIcmsAttribute($value)
    {
        return number_format($value,2,',','.');
    }


    public function setAliquotaIiAttribute($value)
    {
        $this->attributes['aliquota_ii'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaIiAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,',','.');
    }


    public function setAliquotaCofinsImportacaoAttribute($value)
    {
        $this->attributes['aliquota_cofins_importacao'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaCofinsImportacaoAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,',','.');
    }


    public function setAliquotaPisImportacaoAttribute($value)
    {
        $this->attributes['aliquota_pis_importacao'] = floatval(str_replace(',','.',$value));
    }
    public function getAliquotaPisImportacaoAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,',','.');
    }

    //R$
    public function getIcmsAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getIcmsImportacaoAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    // ********************** BELONGS ********************************

    public function ncm()
    {
        return $this->belongsTo('App\Ncm', 'idncm', 'idncm');
    }
    public function categoria_tributacao()
    {
        return $this->belongsTo('App\CategoriaTributacao', 'idcategoria_tributacao', 'idcategoria_tributacao');
    }
    public function origem_tributacao()
    {
        return $this->belongsTo('App\OrigemTributacao', 'idorigem_tributacao', 'idorigem_tributacao');
    }
    public function cst_ipi()
    {
        return $this->belongsTo('App\CstIpi', 'idcst_ipi', 'idcst_ipi');
    }
}
