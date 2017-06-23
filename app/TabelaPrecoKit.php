<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPrecoKit extends Model
{
    use SoftDeletes;
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

    public function kit()
    {
        return $this->belongsTo('App\Kit', 'idkit');
    }
    // ************************** HASMANY **********************************

}
