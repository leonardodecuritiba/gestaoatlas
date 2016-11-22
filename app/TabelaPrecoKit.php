<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TabelaPrecoKit extends Model
{
    public $timestamps = true;
    protected $table = 'tabela_precos_kit';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idtabela_preco',
        'idkit',
        'margem',
        'preco',
        'margem_minimo',
        'preco_minimo',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function tabela_preco()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco');
    }

    public function kit()
    {
        return $this->belongsTo('App\Kit', 'idkit');
    }
    // ************************** HASMANY **********************************

}
