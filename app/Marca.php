<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'marcas';
    protected $primaryKey = 'idmarca';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function instrumento_modelo()
    {
        return $this->belongsTo('App\Models\InstrumentoModelo', 'idmarca');
    }
    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idmarca');
    }
    public function produto()
    {
        return $this->belongsTo('App\Produto', 'idmarca');
    }
    // ************************** HASMANY **********************************
}
