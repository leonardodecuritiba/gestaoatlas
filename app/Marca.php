<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes;
    protected $table = 'marcas';
    protected $primaryKey = 'idmarca';
    public $timestamps = true;
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idpeca');
    }
    public function Instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }
    public function produto()
    {
        return $this->belongsTo('App\Produto', 'idproduto');
    }
    // ************************** HASMANY **********************************
}
