<?php

namespace App\Models\Instrumentos;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class InstrumentoMarca extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'descricao'
    ];

    // ********************** Accessors ********************************
    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    // ********************** BELONGS ********************************
    // ************************** HASMANY **********************************
    public function instrumento_modelos()
    {
        return $this->hasMany('App\Models\Instrumentos\InstrumentoModelo', 'idinstrumento_marca');
    }
}
