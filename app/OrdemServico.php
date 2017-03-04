<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class OrdemServico extends Model
{
    use SoftDeletes;
    const _STATUS_ABERTA_ = 1;
    const _STATUS_ATENDIMENTO_EM_ANDAMENTO_ = 2;
    const _STATUS_FINALIZADA_ = 3;
    const _STATUS_AGUARDANDO_PECA_ = 4;
    const _STATUS_EQUIPAMENTO_NA_OFICINA_ = 5; //primary
    const _STATUS_FATURADA_ = 6; //warning
    public $timestamps = true; //danger
    protected $table = 'ordem_servicos'; //warning
    protected $primaryKey = 'idordem_servico'; //warning
    protected $fillable = [
        'idcliente',
        'idcolaborador',
        'idsituacao_ordem_servico',
        'idcentro_custo',
        'numero_chamado',
        'responsavel',
        'responsavel_cpf',
        'responsavel_cargo',
        'valor_total',
        'desconto',
        'valor_final',
        'custos_deslocamento',
        'custos_isento',
        'pedagios',
        'outros_custos',
        'validacao',
    ]; //success


    // ******************** FUNCTIONS ******************************

    static public function centro_custo_os($idcentro_custo, $situacao_ordem_servico)
    {
        $query = self::filter_situacao($situacao_ordem_servico);
        return $query->where('idcentro_custo', $idcentro_custo);
    }

    static public function filter_situacao($situacao_ordem_servico)
    {

        $query = OrdemServico::orderBy('created_at', 'desc');
        switch ($situacao_ordem_servico) {
            case 'a-faturar':
                $query->where('idsituacao_ordem_servico', '<', 6);
                break;
            case 'faturadas':
                $query->where('idsituacao_ordem_servico', 6);
                break;
        }
        $User = Auth::user();
        if ($User->hasRole('tecnico')) {
            $query->where('idcolaborador', $User->colaborador->idcolaborador);
        }
//        dd($query);
        return $query;
    }

    static public function abrir(Cliente $Cliente, $idcolaborador)
    {
        $data = [
            'idcliente' => $Cliente->idcliente,
            'idcolaborador' => $idcolaborador,
            'idcentro_custo' => $Cliente->idcliente_centro_custo,
            'custos_deslocamento' => $Cliente->custo_deslocamento(),
            'pedagios' => $Cliente->pedagios,
            'outros_custos' => $Cliente->outros_custos,
            'idsituacao_ordem_servico' => 1
        ];
        return self::create($data);
    }

    public function getResponsavelCpfAttribute($value)
    {
        return DataHelper::mask($value, '###.###.###-##');
    }

    public function getStatus()
    {
        return $this->situacao->description;
    }

    public function status() //RETORNA O STATUS 0:ABERTA 1:FECHADA
    {
        return (($this->attributes['fechamento'] != NULL) && ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FINALIZADA_)) ? 1 : 0;
    }

    public function getStatusType()
    {
        switch ($this->attributes['idsituacao_ordem_servico']) {
            case self::_STATUS_ABERTA_:
                return 'info';
            case self::_STATUS_FINALIZADA_:
                return 'danger';
            case self::_STATUS_FATURADA_:
                return 'success';
            default:
                return 'warning';
        }
    }

    public function fechar($request)
    {
        if (isset($request['custos_isento'])) {
            $this->attributes['custos_isento'] = 1;
            $this->attributes['custos_deslocamento'] = 0;
            $this->attributes['pedagios'] = 0;
            $this->attributes['outros_custos'] = 0;
            $this->update_valores();
        }
        $this->attributes['numero_chamado'] = $request['numero_chamado'];
        $this->attributes['responsavel'] = $request['responsavel'];
        $this->attributes['responsavel_cpf'] = $request['responsavel_cpf'];
        $this->attributes['responsavel_cargo'] = $request['responsavel_cargo'];
        $this->attributes['fechamento'] = Carbon::now()->toDateTimeString();
        $this->attributes['idsituacao_ordem_servico'] = 3;
        return $this->save();
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

    public function getValores()
    {
        $this->update_valores();
        $valor_total_servicos = 0;
        $valor_total_pecas = 0;
        $valor_total_kits = 0;
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $valor_total_servicos += $aparelho_manutencao->getTotalServicos();
            $valor_total_pecas += $aparelho_manutencao->getTotalPecas();
            $valor_total_kits += $aparelho_manutencao->getTotalKits();
        }
        $data['valor_total_servicos_float'] = $valor_total_servicos;
        $data['valor_total_servicos'] = 'R$ ' . DataHelper::getFloat2Real($valor_total_servicos);
        $data['valor_total_pecas_float'] = $valor_total_pecas;
        $data['valor_total_pecas'] = 'R$ ' . DataHelper::getFloat2Real($valor_total_pecas);
        $data['valor_total_kits_float'] = $valor_total_kits;
        $data['valor_total_kits'] = 'R$ ' . DataHelper::getFloat2Real($valor_total_kits);

        $data['valor_deslocamento'] = 'R$ ' . $this->custos_deslocamento;
        $data['pedagios'] = 'R$ ' . $this->pedagios;
        $data['outros_custos'] = 'R$ ' . $this->outros_custos;
        $data['valor_outras_despesas_float'] = $this->attributes['custos_deslocamento'] + $this->attributes['pedagios'] + $this->attributes['outros_custos'];

        $data['valor_total'] = 'R$ ' . $this->valor_total;
        $data['valor_final'] = 'R$ ' . $this->valor_final;
        $data['valor_total_float'] = $this->attributes['valor_total'];
        $data['valor_final_float'] = $this->attributes['valor_final'];
        return json_encode($data);
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

    public function aparelho_instrumentos()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idordem_servico')->whereNotNull('idinstrumento');
    }

    public function aparelho_equipamentos()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idordem_servico')->whereNotNull('idequipamento');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function remover()
    {
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $aparelho_manutencao->remover();
        }
        $this->forceDelete();
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    public function centro_custo()
    {
        return $this->belongsTo('App\Cliente', 'idcentro_custo');
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
