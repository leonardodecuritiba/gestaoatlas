<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdemServico extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'ordem_servicos';
    protected $primaryKey = 'idordem_servico';
    protected $fillable = [
        'idcliente',
        'idcolaborador',
        'idsituacao_ordem_servico',
        'fechamento',
        'numero_chamado',
        'valor_total',
        'desconto',
        'valor_final',
        'custos_deslocamento',
        'pedagios',
        'outros_custos',
        'validacao',
    ];


    // ******************** FUNCTIONS ******************************
    public function status() //RETORNA O STATUS 0:ABERTA 1:FECHADA
    {
        return (($this->attributes['fechamento'] != NULL) && ($this->attributes['idsituacao_ordem_servico'] == 3)) ? 1 : 0;
    }
    public function setCustosDeslocamentoAttribute($value)
    {
        $this->attributes['custos_deslocamento'] = DataHelper::getReal2Float($value);
    }
    public function setPedagiosAttribute($value)
    {
        $this->attributes['pedagios'] = DataHelper::getReal2Float($value);
    }
    public function setOutrosCustosAttribute($value)
    {
        $this->attributes['outros_custos'] = DataHelper::getReal2Float($value);
    }


    public function getCustosDeslocamentoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getPedagiosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function getOutrosCustosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    public function getFechamentoAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    public function has_aparelho_manutencaos()
    {
        return ($this->aparelho_manutencaos()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function aparelho_manutencaos()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idordem_servico');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    public function colaborador()
    {
        return $this->belongsTo('App\Colaborador', 'idcolaborador');
    }

    // ************************** HASMANY **********************************

    public function situacao()
    {
        return $this->belongsTo('App\SituacaoOrdemServico', 'idsituacao_ordem_servico');
    }


}
