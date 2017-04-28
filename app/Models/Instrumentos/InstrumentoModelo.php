<?php

namespace App\Models\Instrumentos;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class InstrumentoModelo extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'idinstrumento_marca',
        'descricao'
    ];

    public function getMarcaModelo()
    {
        return $this->marca->descricao . ' / ' . $this->descricao;
    }

    // ********************** Accessors ********************************
    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    // ********************** BELONGS ********************************
    public function marca()
    {
        return $this->belongsTo('App\Models\Instrumentos\InstrumentoMarca', 'idinstrumento_marca');
    }

    // ************************** HASMANY **********************************
    public function instrumento_bases()
    {
        return $this->hasMany('App\Models\Instrumentos\InstrumentoBase', 'idmodelo');
    }
}
