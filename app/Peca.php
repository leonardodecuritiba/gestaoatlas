<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    public $timestamps = true;
    protected $table = 'pecas';
    protected $primaryKey = 'idpeca';
    protected $fillable = [
        'idfornecedor',
        'idpeca_tributacao',
        'idmarca',
        'idgrupo',
        'idunidade',
        'tipo',
        'codigo_auxiliar',
        'codigo_barras',
        'descricao',
        'descricao_tecnico',
        'foto',
        'sub_grupo',
        'garantia',
        'comissao_tecnico',
        'comissao_vendedor',
    ];

    // ******************** FUNCTIONS ******************************

	static public function getAlltoSelectList() {
		return self::get()->map( function ( $s ) {
			return [
				'id'          => $s->idpeca,
				'description' => $s->descricao . ' - ' . $s->nome_marca()
			];
		} )->pluck( 'description', 'id' );
	}

    public function has_insumos()
    {
        return ($this->insumos()->count() > 0);
    }

    public function insumos()
    {
        return $this->hasMany('App\Insumo', 'idinsumo');
    }

    public function getFoto()
    {
        return ($this->foto != '') ? asset('uploads/' . $this->table . '/' . $this->foto) : asset('imgs/cogs.png');
    }

    public function getFotoThumb()
    {
        return ($this->foto != '') ? asset('uploads/' . $this->table . '/thumb_' . $this->foto) : asset('imgs/cogs.png');
    }

    public function setTipoAttribute($value)
    {
        $this->attributes['tipo'] = ($value == 'Peça' || $value == 'peca') ? 'peca' : 'produto';
    }

    public function getTipo()
    {
        return ($this->attributes['tipo'] == 'peca') ? 'Peça' : 'Produto';
    }

    public function setComissaoTecnicoAttribute($value)
    {
        $this->attributes['comissao_tecnico'] = DataHelper::getPercent2Float($value);
    }

    public function getComissaoTecnicoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setComissaoVendedorAttribute($value)
    {
        $this->attributes['comissao_vendedor'] = DataHelper::getPercent2Float($value);
    }

    public function getComissaoVendedorAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function has_fornecedor()
    {
        return $this->fornecedor()->count();
    }

    public function fornecedor()
    {
        return $this->belongsTo('App\Fornecedor', 'idfornecedor');
    }

    public function peca_tributacao()
    {
        return $this->belongsTo('App\PecaTributacao', 'idpeca_tributacao');
    }

    public function nome_marca()
    {
        $marca = $this->hasOne('App\Marca', 'idmarca', 'idmarca')->first();
        return ($marca != NULL) ? $marca->descricao : 'Sem Marca';
    }
    public function marca()
    {
        return $this->hasOne('App\Marca', 'idmarca', 'idmarca');
    }

    // ********************** BELONGS ********************************

    public function unidade()
    {
        return $this->hasOne('App\Unidade', 'idunidade', 'idunidade');
    }


    public function grupo()
    {
        return $this->hasOne('App\Grupo', 'idgrupo', 'idgrupo');
    }

    // ************************** HASMANY **********************************
    public function tabela_preco_by_name($value)
    {
        $id = TabelaPreco::where('descricao', $value)->pluck('idtabela_preco');
        return $this->hasMany('App\TabelaPrecoPeca', 'idpeca')
            ->where('idtabela_preco', $id)
            ->first();
    }

    public function tabela_preco()
    {
        return $this->hasMany('App\TabelaPrecoPeca', 'idpeca');
    }

    public function pecas_utilizadas()
    {
        return $this->hasMany('App\PecasUtilizadas', 'idpeca');
    }

    public function peca_kits()
    {
        return $this->hasMany('App\PecaKit', 'idpeca');
    }

    public function tabela_cliente($idtabela_preco)
    {
        $tabela_preco = $this->tabela_preco_cliente($idtabela_preco)->first();
        return (count($tabela_preco) > 0) ? $tabela_preco : 0;
    }

    public function tabela_preco_cliente($idtabela_preco)
    {
        return $this->hasMany('App\TabelaPrecoPeca', 'idpeca')->where('idtabela_preco', $idtabela_preco);
    }
}
