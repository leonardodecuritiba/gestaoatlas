<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class TabelaPrecoServico extends Model
{
    public $timestamps = true;
    protected $table = 'tabela_precos_servico';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idtabela_preco',
        'idservico',
        'margem',
        'preco',
        'margem_minimo',
        'preco_minimo',
    ];

    public function getMargemAttribute($value)
    {
        return DataHelper::getFloat2Percent($value);
    }

    public function getMargemMinimoAttribute($value)
    {
        return DataHelper::getFloat2Percent($value);
    }

    public function getPrecoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getPrecoMinimoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function tabela_preco()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco');
    }

    public function servico()
    {
        return $this->belongsTo('App\Servico', 'idservico');
    }
    // ************************** HASMANY **********************************

}
