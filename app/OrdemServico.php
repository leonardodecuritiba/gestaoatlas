<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class OrdemServico extends Model
{
    use SoftDeletes;
    const _STATUS_ABERTA_ = 1;
    const _STATUS_ATENDIMENTO_EM_ANDAMENTO_ = 2;
    const _STATUS_FINALIZADA_ = 3;
    const _STATUS_AGUARDANDO_PECA_ = 4;
    const _STATUS_EQUIPAMENTO_NA_OFICINA_ = 5; //primary
    const _STATUS_FATURADA_ = 6; //success
    public $timestamps = true;
    public $valores = [];
    protected $table = 'ordem_servicos';
    protected $primaryKey = 'idordem_servico';
    protected $fillable = [
        'idcliente',
        'idfechamento',
        'idcolaborador',
        'idsituacao_ordem_servico',
        'idcentro_custo',
        'data_fechada',
        'data_finalizada',
        'numero_chamado',
        'responsavel',
        'responsavel_cpf',
        'responsavel_cargo',
        'valor_total',
        'desconto_tecnico',
        'acrescimo_tecnico',
        'valor_final',
        'custos_deslocamento',
        'custos_isento',
        'pedagios',
        'outros_custos',
        'validacao',
    ]; //success

    private $valor_desconto, $valor_acrescimo;

    // ******************** FUNCTIONS ******************************


    static public function getValoresPosFatoramento(Collection $OrdemServicos)
    {
//        return $this->ordem_servicos[0]->aparelho_manutencaos[1]->servico_prestados->sum('valor');
//        return $this->ordem_servicos[0]->aparelho_manutencaos[1]->getTotalServicos();

        //substr ($string, $start, $length = null) {}
        $_valores = [
            'valor_desconto_float' => 0,
            'valor_acrescimo_float' => 0,
            'valor_desconto_servicos_float' => 0,
            'valor_desconto_pecas_float' => 0,
            'valor_desconto_kits_float' => 0,
            'valor_total_servicos_float' => 0,
            'valor_total_pecas_float' => 0,
            'valor_total_kits_float' => 0,
            'valor_outros_custos_float' => 0,
            'valor_deslocamento_float' => 0,
            'valor_pedagios_float' => 0,
            'valor_outras_despesas_float' => 0,
            'valor_total_float' => 0,
            'valor_final_float' => 0,
        ];
        foreach ($OrdemServicos as $ordem_servico) {
            $valores = $ordem_servico->setValores();
            foreach ($valores as $key => $value) {
                $_valores[$key] += floatval($value);
            }
        }

//        switch ($this->cliente->idemissao_tecnica) {
//            case TipoEmissaoFaturamento::_TIPO_BOLETO_NFE_NFSE_:
//                $_valores['valor_nfse_float'] = $_valores['valor_outras_despesas_float'] + $_valores['valor_total_servicos_float'];
//                break;
//            case TipoEmissaoFaturamento::_TIPO_BOLETO_NFSE_AGREGADO_PECA_:
//                $_valores['valor_nfse_float'] = $_valores['valor_final_float'];
//                break;
//            default:
//                $_valores['valor_nfse_float'] = 0;
//                break;
//        }

        foreach ($_valores as $key => $value) {
            $nkey = substr($key, 0, strlen($key) - 6);
            $_valores[$nkey] = DataHelper::getFloat2RealMoeda($value);
        }
        return (object)$_valores;
    }

    //VISUALIZAÇÃO NO FATURAMENTOS
    static public function filter_layout($data)
    {
        $query = self::filter_situacao($data);
        return (isset($data['centro_custo']) && $data['centro_custo']) ? $query->centroCustos() : $query->clientes();
    }

    static public function filter_situacao($data)
    {
        $data['situacao'] = (isset($data['situacao'])) ? $data['situacao'] : NULL;
        $query = OrdemServico::orderBy('idordem_servico', 'desc');
        switch ($data['situacao']) {
            case self::_STATUS_ATENDIMENTO_EM_ANDAMENTO_:
                $query->where('idsituacao_ordem_servico', self::_STATUS_ATENDIMENTO_EM_ANDAMENTO_);
                break;
            case self::_STATUS_FINALIZADA_:
                $query->where('idsituacao_ordem_servico', self::_STATUS_FINALIZADA_);
                break;
            case self::_STATUS_ABERTA_:
                $query->where('idsituacao_ordem_servico', self::_STATUS_ABERTA_);
                break;
            case self::_STATUS_FATURADA_:
                $query->where('idsituacao_ordem_servico', self::_STATUS_FATURADA_);
                break;
//            default:
//                $query->where('idsituacao_ordem_servico', self::_STATUS_ABERTA_);
//                break;
        }
        if (isset($data['data'])) {
            $query->where('created_at', '>=', DataHelper::getPrettyToCorrectDateTime($data['data']));
        }

        $User = Auth::user();
        if ($User->hasRole('tecnico')) {
            $query->where('idcolaborador', $User->colaborador->idcolaborador);
        }
//        dd($query);
        return $query;
    }

    static public function getSituacaoSelect()
    {
//        if (Auth::user()->hasRole(['admin', 'financeiro'])){
//            $retorno = [
//                'todas' => 'Todas',
//                'abertas' => 'Abertas',
//                'atendimento-em-andamento' => 'Em Atendimento',
//                'finalizadas' => 'Finalizadas',
//                'pendentes' => 'Pendentes',
//                'faturadas' => 'Faturadas',
//            ];
//        } else {
//            $retorno = [
//                'todas' => 'Todas',
//                'abertas' => 'Abertas',
//                'atendimento-em-andamento' => 'Em Atendimento',
//                'finalizadas' => 'Finalizadas',
//                'pendentes' => 'Pendentes',
//                'faturadas' => 'Faturadas',
//            ];
//        }
//        return [
//            'abertas' => 'Abertas',
//            'atendimento-em-andamento' => 'Em Atendimento',
//            'finalizadas' => 'Finalizadas'
//        ];
        return [
            'todas' => 'Todas',
            self::_STATUS_ABERTA_ => 'Abertas',
            self::_STATUS_ATENDIMENTO_EM_ANDAMENTO_ => 'Em Atendimento',
            self::_STATUS_FINALIZADA_ => 'Finalizadas',
            self::_STATUS_FATURADA_ => 'Faturadas',
        ];
    }

    static public function filterByIdTecnicoDate($data)
    {
        $query = self::getByIDtecnico($data['idtecnico']);
        if ($data['data_inicial'] != "") {
            $query->where('created_at', '>=', DataHelper::getPrettyToCorrectDateTime($data['data_inicial']));
        }
        if ($data['data_final'] != "") {
            $query->where('created_at', '<=', DataHelper::getPrettyToCorrectDateTime($data['data_final']));
        }
        return $query;
    }

    static public function getByIDtecnico($idtecnico)
    {
        if ($idtecnico == 0) {
            $query = OrdemServico::whereNotNull('idcolaborador');
        } else {
            $Tecnico = Tecnico::findOrFail($idtecnico);
            $query = self::where('idcolaborador', $Tecnico->idcolaborador);
        }
        return $query;
    }

    static public function centro_custo_os($data)
    {
        $query = self::filter_situacao_cliente($data);
        return $query->where('idcentro_custo', $data['idcentro_custo']);
    }

    static public function filter_situacao_cliente($data)
    {
        $query = self::filter_situacao($data)
            ->with('cliente', 'colaborador');
        if (isset($data['idcliente']) && ($data['idcliente'] != "")) {
            $query->where('idcliente', $data['idcliente']);
        }
        return $query;
    }

    static public function filter_situacao_centro_custo($data)
    {
        $query = self::filter_situacao($data)
            ->with('centro_custo')
            ->whereNotNull('idcentro_custo')
            ->groupBy('idcentro_custo');
        if (isset($data['idcentro_custo']) && ($data['idcentro_custo'] != "")) {
            $query->where('idcentro_custo', $data['idcentro_custo']);
        }
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

    static public function reabrir($idordem_servico)
    {
        $OrdemServico = self::find($idordem_servico);
        $OrdemServico->update([
            'data_fechada' => NULL,
            'data_finalizada' => NULL,
            'idsituacao_ordem_servico' => self::_STATUS_ABERTA_,
        ]);
        return true;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCentroCustos($query)
    {
        return $query->whereNotNull('idcentro_custo');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClientes($query)
    {
        return $query->whereNull('idcentro_custo');
    }

    public function getStatusFinalizada()
    {
        return ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FINALIZADA_);
    }

    public function getStatusFechada()
    {
//        return !($this->idsituacao_ordem_servico == self::_STATUS_A_FATURAR_ || $this->idsituacao_ordem_servico == self::_STATUS_FATURADA_);
        return !($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FATURADA_
            || $this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FATURADA_);
    }

    public function setFaturamento($idfechamento)
    {
        $this->attributes['idfechamento'] = $idfechamento;
        $this->attributes['idsituacao_ordem_servico'] = self::_STATUS_FATURADA_;
        return $this->save();
    }

    public function unsetFaturamento()
    {
        $this->attributes['idfechamento'] = NULL;
        $this->attributes['idsituacao_ordem_servico'] = self::_STATUS_FINALIZADA_;
        return $this->save();
    }

    public function aplicaValores($data)
    {
        $valor = DataHelper::getReal2Float($data['valor']);
        if ($data['tipo']) { //acrescimo
            $max = $this->tecnico->acrescimo_max_float();
            $this->attributes['acrescimo_tecnico'] = ($valor > $max) ? $max : $valor;
        } else { //desconto
            $max = $this->tecnico->desconto_max_float();
            $this->attributes['desconto_tecnico'] = ($valor > $max) ? $max : $valor;
        }
        $this->save();
        return $this->update_valores();

    }

    public function update_valores()
    {
        $this->attributes['valor_total'] = $this->get_valor_total();

        $this->attributes['valor_final'] =
            $this->attributes['valor_total'] +
            $this->attributes['custos_deslocamento'] +
            $this->attributes['pedagios'] +
            $this->attributes['outros_custos'];

        $this->save();
        return 1;
    }

    public function get_valor_total()
    {
        $valor_total = $valor_servicos = $valor_pecas = $valor_kits = 0;
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $valor_servicos += $aparelho_manutencao->getTotalServicos();
            $valor_pecas += $aparelho_manutencao->getTotalPecas();
            $valor_kits += $aparelho_manutencao->getTotalKits();
        }

        $this->valor_desconto = ($this->attributes['desconto_tecnico'] * $valor_servicos / 100);
        $this->valor_acrescimo = ($this->attributes['acrescimo_tecnico'] * $valor_servicos / 100);
        $valor_total = $valor_servicos + $valor_pecas + $valor_kits
            - $this->valor_desconto + $this->valor_acrescimo;
        return $valor_total;
    }

    public function get_desconto_tecnico_real()
    {
        return DataHelper::getFloat2Real($this->attributes['desconto_tecnico']);
    }

    public function get_acrescimo_tecnico_real()
    {
        return DataHelper::getFloat2Real($this->attributes['acrescimo_tecnico']);
    }

    public function getResponsavelCpfAttribute($value)
    {
        return DataHelper::mask($value, '###.###.###-##');
    }

//    public function getStatus()
//    {
//        return $this->situacao->descricao;
//    }


    public function getStatusText()
    {
        return $this->situacao->descricao;
    }

    public function status() //RETORNA O STATUS 0:ABERTA 1:FECHADA
    {
        return (
            ($this->attributes['data_finalizada'] != NULL)
            &&
            (
                ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FINALIZADA_)
                ||
                ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FINALIZADA_)
                ||
                ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_FATURADA_)
//                ($this->attributes['idsituacao_ordem_servico'] == self::_STATUS_A_FATURAR_)

            )
        ) ? 1 : 0;
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
        $this->attributes['data_finalizada'] = Carbon::now()->toDateTimeString();
        $this->attributes['idsituacao_ordem_servico'] = self::_STATUS_FINALIZADA_;
        return $this->save();
    }

    public function getValoresObj()
    {
        return json_decode($this->getValores());
    }

    public function getValores()
    {
        $this->update_valores();
        $this->setValores();
        if ($this->desconto_tecnico > 0) {
            $this->valores['valor_desconto'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_desconto_float']);
        }
        if ($this->acrescimo_tecnico > 0) {
            $this->valores['valor_acrescimo'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_acrescimo_float']);
        }

        $this->valores['valor_total_servicos'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_total_servicos_float']);
        $this->valores['valor_total_pecas'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_total_pecas_float']);
        $this->valores['valor_total_kits'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_total_kits_float']);

        $this->valores['valor_desconto_servicos'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_desconto_servicos_float']);
        $this->valores['valor_desconto_pecas'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_desconto_pecas_float']);
        $this->valores['valor_desconto_kits'] = 'R$ ' . DataHelper::getFloat2Real($this->valores['valor_desconto_kits_float']);

        $this->valores['valor_deslocamento'] = 'R$ ' . $this->attributes['custos_deslocamento'];
        $this->valores['valor_pedagios'] = 'R$ ' . $this->attributes['pedagios'];
        $this->valores['valor_outros_custos'] = 'R$ ' . $this->attributes['outros_custos'];

        $this->valores['valor_total'] = 'R$ ' . $this->attributes['valor_total'];
        $this->valores['valor_final'] = 'R$ ' . DataHelper::getFloat2Real($this->attributes['valor_final']);
        return json_encode($this->valores);
    }

    public function setValores()
    {
        $valor_total_servicos = $valor_total_pecas = $valor_total_kits = 0;
        $valor_desconto_servicos = $valor_desconto_pecas = $valor_desconto_kits = 0;


        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $valor_total_servicos += $aparelho_manutencao->getTotalServicos();
            $valor_total_pecas += $aparelho_manutencao->getTotalPecas();
            $valor_total_kits += $aparelho_manutencao->getTotalKits();
            $valor_desconto_servicos += $aparelho_manutencao->getTotalDescontoServicos();
            $valor_desconto_pecas += $aparelho_manutencao->getTotalDescontoPecas();
            $valor_desconto_kits += $aparelho_manutencao->getTotalDescontoKits();
        }
        if ($this->desconto_tecnico > 0) {
            $this->valores['valor_desconto_float'] = $this->valor_desconto;
        }
        if ($this->acrescimo_tecnico > 0) {
            $this->valores['valor_acrescimo_float'] = $this->valor_acrescimo;
        }

        $this->valores['valor_desconto_servicos_float'] = $valor_desconto_servicos;
        $this->valores['valor_desconto_pecas_float'] = $valor_desconto_pecas;
        $this->valores['valor_desconto_kits_float'] = $valor_desconto_kits;

        $this->valores['valor_total_servicos_float'] = $valor_total_servicos;
        $this->valores['valor_total_pecas_float'] = $valor_total_pecas;
        $this->valores['valor_total_kits_float'] = $valor_total_kits;

        $this->valores['valor_outros_custos_float'] = $this->attributes['outros_custos'];
        $this->valores['valor_deslocamento_float'] = $this->attributes['custos_deslocamento'];
        $this->valores['valor_pedagios_float'] = $this->attributes['pedagios'];
        $this->valores['valor_outras_despesas_float'] = $this->attributes['custos_deslocamento'] + $this->attributes['pedagios'] + $this->attributes['outros_custos'];
        $this->valores['valor_total_float'] = $this->attributes['valor_total'];
        $this->valores['valor_final_float'] = $this->attributes['valor_final'];
        return $this->valores;
    }

    public function remover()
    {
        foreach ($this->aparelho_manutencaos as $aparelho_manutencao) {
            $aparelho_manutencao->remover();
        }
        $this->forceDelete();
    }


    // ******************** ACCESSORS ******************************

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

    public function getDataAbertura()
    {
        return DataHelper::getPrettyDateTime($this->getAttributeValue('created_at'));
    }

    public function getDataFinalizadaAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

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

    public function tecnico()
    {
        return $this->colaborador->tecnico();
    }
    // ************************** HASMANY **********************************

    public function situacao()
    {
        return $this->belongsTo('App\SituacaoOrdemServico', 'idsituacao_ordem_servico');
    }

    public function faturamento()
    {
        return $this->belongsTo('App\Models\Faturamento', 'idfechamento');
    }


}
