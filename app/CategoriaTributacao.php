<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaTributacao extends Model
{
    use SoftDeletes;
    protected $table = 'categoria_tributacao';
    protected $primaryKey = 'idcategoria_tributacao';
    public $timestamps = true;
    protected $fillable = [
        'codigo',
        'descricao'
    ];

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function tributacao()
    {
        return $this->hasOne('App\Tributacao', 'idcategoria_tributacao', 'idcategoria_tributacao');
    }
    // ********************** BELONGS ********************************
}
