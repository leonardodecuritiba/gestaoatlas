<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaTributacao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'categoria_tributacao';
    protected $primaryKey = 'idcategoria_tributacao';
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
