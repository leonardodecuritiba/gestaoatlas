<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SegmentoFornecedor extends Model
{
    use SoftDeletes;
    protected $table = 'segmentos_fornecedores';
    protected $primaryKey = 'idsegmento_fornecedor';
    public $timestamps = true;
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function fornecedor()
    {
        return $this->belongsTo('App\Fornecedor', 'idfornecedor');
    }
    // ************************** HASMANY **********************************
}
