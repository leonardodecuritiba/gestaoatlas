<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CstIpi extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'cst_ipi';
    protected $primaryKey = 'idcst_ipi';
    protected $fillable = [
        'codigo',
        'descricao'
    ];

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function tributacao()
    {
        return $this->hasOne('App\Tributacao', 'idcst_ipi', 'idcst_ipi');
    }
    // ********************** BELONGS ********************************
}
