<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrigemTributacao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'origem_tributacao';
    protected $primaryKey = 'idorigem_tributacao';
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
