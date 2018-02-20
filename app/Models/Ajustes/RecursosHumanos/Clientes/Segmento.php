<?php

namespace App\Models\Ajustes\RecursosHumanos\Clientes;

use App\Cliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Segmento extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'segmentos';
    protected $primaryKey = 'idsegmento';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcliente');
    }
    // ************************** HASMANY **********************************
}
