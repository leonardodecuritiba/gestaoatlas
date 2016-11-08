<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regiao extends Model
{
    use SoftDeletes;
    protected $table = 'regioes';
    protected $primaryKey = 'idregiao';
    public $timestamps = true;
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }
    // ************************** HASMANY **********************************
}
