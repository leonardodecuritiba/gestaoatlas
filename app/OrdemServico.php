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
    public function getValores()
    {
        $this->update_valores();
        $valor_total_servicos = 0;
        foreach ($this->instrumentos_manutencao as $instrumentos_manutencao) {
            $valor_total_servicos += $instrumentos_manutencao->getTotalServicos();
        }
        $data['valor_total_servicos'] = DataHelper::getFloat2Real($valor_total_servicos);

        $valor_total_pecas = 0;
        foreach ($this->instrumentos_manutencao as $instrumentos_manutencao) {
            $valor_total_pecas += $instrumentos_manutencao->getTotalPecas();
        }
        $data['valor_total_pecas'] = DataHelper::getFloat2Real($valor_total_pecas);

        $valor_total_kits = 0;
        foreach ($this->instrumentos_manutencao as $instrumentos_manutencao) {
            $valor_total_kits += $instrumentos_manutencao->getTotalKits();
        }
        $data['valor_total_kits'] = DataHelper::getFloat2Real($valor_total_kits);
        $data['valor_deslocamento'] = $this->custos_deslocamento;
        $data['pedagios'] = $this->pedagios;
        $data['outros_custos'] = $this->outros_custos;
        $data['valor_total'] = $this->valor_total;
        $data['valor_final'] = $this->valor_final;
        return json_encode($data);
    }

    public function update_valores()
    {
        $this->attributes['valor_total'] = 0;
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $this->attributes['valor_total'] += $aparelho_manutencao->get_total();
        }
        $this->attributes['valor_final'] = $this->attributes['valor_total'] + $this->attributes['custos_deslocamento'] + $this->attributes['pedagios'] + $this->attributes['outros_custos'];
        $this->save();
        return 1;
    }
    public function status() //RETORNA O STATUS 0:ABERTA 1:FECHADA
    {
        return (($this->attributes['fechamento'] != NULL) && ($this->attributes['idsituacao_ordem_servico'] == 3)) ? 1 : 0;
    }

    public function getCustosDeslocamentoAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function setCustosDeslocamentoAttribute($value)
    {
        $this->attributes['custos_deslocamento'] = DataHelper::getReal2Float($value);
    }

    public function getPedagiosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function setPedagiosAttribute($value)
    {
        $this->attributes['pedagios'] = DataHelper::getReal2Float($value);
    }

    public function getOutrosCustosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function setOutrosCustosAttribute($value)
    {
        $this->attributes['outros_custos'] = DataHelper::getReal2Float($value);
    }


    public function getValorTotalAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getValorFinalAttribute($value)
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

    public function aparelho_manutencaos()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idordem_servico');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function remover()
    {
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $aparelho_manutencao->remover();
        }
        $this->delete();
    }

    public function instrumentos_manutencao()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idordem_servico')->whereNotNull('idinstrumento');
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
