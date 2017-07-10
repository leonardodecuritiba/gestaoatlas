<?php

namespace App\Models\Instrumentos;

use App\Helpers\DataHelper;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;

class InstrumentoBase extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'idmodelo',
        'descricao',
        'divisao',
        'portaria',
        'capacidade',
        'foto'
    ];

    // ********************** Accessors ********************************
    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getDetalhesBase()
    {
        return $this->getMarcaModelo() . ' - ' .
            $this->attributes['divisao'] . ' - ' .
            $this->attributes['portaria'] . ' - ' .
            $this->attributes['capacidade'];
    }

    public function getMarcaModelo()
    {
        return $this->modelo->getMarcaModelo();
    }

    public function getFoto()
    {
        return ($this->foto != NULL) ? ImageHelper::getFullPath('instrumento_bases') . $this->foto : asset('imgs/cogs.png');
    }

    public function getThumbFoto()
    {
        return ($this->foto != NULL) ? ImageHelper::getFullThumbPath('instrumento_bases') . $this->foto : asset('imgs/cogs.png');
    }

    // ********************** BELONGS ********************************
    public function modelo()
    {
        return $this->belongsTo('App\Models\Instrumentos\InstrumentoModelo', 'idmodelo');
    }

    // ************************** HASMANY **********************************
    public function instrumentos()
    {
        return $this->hasMany('App\Instrumentos', 'idbase');
    }
}
