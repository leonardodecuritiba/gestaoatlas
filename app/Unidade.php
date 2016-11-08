<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use SoftDeletes;
    protected $table = 'unidades';
    protected $primaryKey = 'idunidade';
    public $timestamps = true;
    protected $fillable = [
        'codigo',
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
    // ************************** HASMANY **********************************
}
