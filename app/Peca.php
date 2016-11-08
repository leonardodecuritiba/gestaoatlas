<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    protected $table = 'pecas';
    protected $primaryKey = 'idpeca';
    public $timestamps = true;
    /*static public $required = [
        'codigo',
        'descricao',
        'descricao_tecnico',
        'marca',
        'unidade',
        'grupo',
        'comissao_tecnico',
        'comissao_vendedor',
        'custo_compra',
        'custo_imposto',
        'custo_final',
        'preco',
        'preco_frete',
        'preco_imposto',
        'preco_final'
    ];*/
    protected $fillable = [
//        'idtributacao',
        'idfornecedor',
        'idmarca',
        'idgrupo',
        'idunidade',
        'tipo',
        'codigo',
        'codigo_auxiliar',
        'codigo_barras',
        'descricao',
        'descricao_tecnico',
        'foto',
        'sub_grupo',
        'garantia',
        'comissao_tecnico',
        'comissao_vendedor',
        'custo_final',
        /*
        'custo_compra',
        'custo_frete',
        'custo_imposto',
        'custo_final',
        'custo_dolar',
        'custo_dolar_frete',
        'custo_dolar_cambio',
        'custo_dolar_imposto',
        'preco',
        'preco_frete',
        'preco_imposto',
        'preco_final'
        */
        ];
    // ******************** FUNCTIONS ******************************
    public function has_insumos()
    {
        return ($this->insumos()->count() > 0);
    }

    public function getFoto()
    {
        return ($this->foto!='')?asset('../storage/uploads/'.$this->table.'/'.$this->foto):asset('imgs/cogs.png');
    }
    public function getFotoThumb()
    {
        return ($this->foto!='')?asset('../storage/uploads/'.$this->table.'/thumb_'.$this->foto):asset('imgs/cogs.png');
    }

    public function setTipoAttribute($value)
    {
        $this->attributes['tipo'] = ($value == 'Peça')?'peca':'produto';
    }
    public function getTipoAttribute($value)
    {
        return ($value == 'peca')?'Peça':'Produto';
    }

    public function setComissaoTecnicoAttribute($value)
    {
        $this->attributes['comissao_tecnico'] = floatval(str_replace(',','.',$value));
    }
    public function getComissaoTecnicoAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setComissaoVendedorAttribute($value)
    {
        $this->attributes['comissao_vendedor'] = floatval(str_replace(',','.',$value));
    }
    public function getComissaoVendedorAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function getCustoFinalAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    /*
    public function getCustoCompraAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getCustoImpostoAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getCustoFinalAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getCustoFreteAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getCustoDolarAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,'.',',');
    }
    public function getCustoDolarFreteAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,'.',',');
    }
    public function getCustoDolarCambioAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,'.',',');
    }
    public function getCustoDolarImpostoAttribute($value)
    {
        return ($value==NULL)?$value:number_format($value,2,'.',',');
    }

    public function getPrecoAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getPrecoFreteAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getPrecoImpostoAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getPrecoFinalAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    */

    public function has_fornecedor()
    {
        return $this->fornecedor()->count();
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    /*
    public function tributacao()
    {
        return $this->hasOne('App\Tributacao', 'idtributacao', 'idtributacao');
    }
    */
    public function marca()
    {
        return $this->hasOne('App\Marca', 'idmarca', 'idmarca');
    }
    public function unidade()
    {
        return $this->hasOne('App\Unidade', 'idunidade', 'idunidade');
    }
    public function grupo()
    {
        return $this->hasOne('App\Grupo', 'idgrupo', 'idgrupo');
    }

    // ********************** BELONGS ********************************
    public function fornecedor()
    {
        return $this->belongsTo('App\Fornecedor', 'idfornecedor');
    }

    // ************************** HASMANY **********************************
    public function insumos()
    {
        return $this->hasMany('App\Insumo', 'idinsumo');
    }
}
