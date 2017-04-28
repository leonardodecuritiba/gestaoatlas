<?php

namespace App\Models\Instrumentos;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class InstrumentoSetor extends Model
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

    // ************************** HASMANY **********************************
    public function instrumentos()
    {
        return $this->hasMany('App\Instrumentos', 'idbase');
    }
}
