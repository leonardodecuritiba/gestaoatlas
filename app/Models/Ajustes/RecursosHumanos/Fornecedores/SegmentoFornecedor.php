<?php

namespace App\Models\Ajustes\RecursosHumanos\Fornecedores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SegmentoFornecedor extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'segmentos_fornecedores';
    protected $primaryKey = 'idsegmento_fornecedor';
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
