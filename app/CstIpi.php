<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CstIpi extends Model
{
    use SoftDeletes;
    protected $table = 'cst_ipi';
    protected $primaryKey = 'idcst_ipi';
    public $timestamps = true;
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
