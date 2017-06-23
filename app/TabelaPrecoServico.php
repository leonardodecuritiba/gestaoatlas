<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPrecoServico extends Model
{
    use SoftDeletes;
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

    public function preco_float()
    {
        return $this->attributes['preco'];
    }

    public function preco_minimo_float()
    {
        return $this->attributes['preco_minimo'];
    }

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

    public function getPrecoReal()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['preco_minimo']);
    }

    public function getPrecoMinimoReal()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['preco_minimo']);
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
