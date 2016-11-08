<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrigemTributacao extends Model
{
    use SoftDeletes;
    protected $table = 'origem_tributacao';
    protected $primaryKey = 'idorigem_tributacao';
    public $timestamps = true;
    protected $fillable = [
        'codigo',
        'descricao'
    ];

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function tributacao()
    {
        return $this->hasOne('App\Tributacao', 'idorigem_tributacao', 'idorigem_tributacao');
    }
    // ********************** BELONGS ********************************
}
