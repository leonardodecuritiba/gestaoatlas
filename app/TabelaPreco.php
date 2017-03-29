<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPreco extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'tabela_precos';
    protected $primaryKey = 'idtabela_preco';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    // ************************** HASMANY **********************************

    public function clientes_comercial()
    {
        return $this->hasMany('App\Cliente', 'idtabela_preco_comercial');
    }

    public function clientes_tecnica()
    {
        return $this->hasMany('App\Cliente', 'idtabela_preco_tecnica');
    }

    public function tabela_preco_kit()
    {
        return $this->hasMany('App\TabelaPrecoKit', 'idtabela_preco');
    }

    public function tabela_preco_peca()
    {
        return $this->hasMany('App\TabelaPrecoPeca', 'idtabela_preco');
    }

    public function tabela_preco_servico()
    {
        return $this->hasMany('App\TabelaPrecoServico', 'idtabela_preco');
    }


}
