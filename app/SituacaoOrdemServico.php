<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SituacaoOrdemServico extends Model
{
    use SoftDeletes;
    protected $table = 'situacao_ordem_servicos';
    protected $primaryKey = 'idsituacao_ordem_servico';
    public $timestamps = true;
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function ordem_servicos()
    {
        return $this->hasMany('App\OrdemServico', 'idordem_servico');
    }
}
