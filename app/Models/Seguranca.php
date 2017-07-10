<?php

namespace App\Models;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class Seguranca extends Model
{
    public $timestamps = true;
    protected $table = 'seguranca_criacaos';
    protected $fillable = [
        'idcriador',
        'idvalidador',
        'validated_at'
    ];

    // ********************** Accessors ********************************
    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getValidateAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    // ********************** BELONGS ********************************
    public function criador()
    {
        return $this->belongsTo('App\Colaborador', 'idcriador');
    }

    public function validador()
    {
        return $this->belongsTo('App\Colaborador', 'idvalidador');
    }
}
