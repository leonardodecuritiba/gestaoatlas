<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegurancaCriacao extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'idcriador',
        'idvalidador',
        'validated_at'
    ];

    // ********************** BELONGS ********************************
    public function criador()
    {
        return $this->belongsTo('App\Colaborador', 'idcriador', 'idcolaborador');
    }

    public function validador()
    {
        return $this->belongsTo('App\Colaborador', 'idvalidador', 'idcolaborador');
    }

    // ************************** HASMANY **********************************
    public function instrumento_bases()
    {
        return $this->hasMany('App\Models\InstrumentoBase', 'idmodelo');
    }
}
