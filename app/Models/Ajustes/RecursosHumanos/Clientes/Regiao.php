<?php

namespace App\Models\Ajustes\RecursosHumanos\Clientes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regiao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'regioes';
    protected $primaryKey = 'idregiao';
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
