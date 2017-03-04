<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPrecoPeca extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'tabela_precos_peca';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idtabela_preco',
        'idpeca',
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

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function tabela_preco()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco');
    }

    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idpeca');
    }
    // ************************** HASMANY **********************************

}
