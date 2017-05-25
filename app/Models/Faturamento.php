<?php

namespace App\Models;

use App\Models\NotasFiscais\NF;
use App\Models\NotasFiscais\NFe;
use App\Models\NotasFiscais\NFSe;

use App\Scopes\LastCreatedScope;
use App\Ajuste;
use App\AparelhoManutencao;
use App\Helpers\DataHelper;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Faturamento extends Model
{
//    const _STATUS_FATURAMENTO_PENDENTE_ = 1;
    const _STATUS_PAGAMENTO_PENDENTE_ = 1;//danger
    const _STATUS_FATURADO_ = 2; //success
    public $timestamps = true;
    protected $table = 'fechamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idcliente',
        'idstatus_fechamento',
        'idpagamento',
        'idnfe_homologacao',
        'idnfe_producao',
        'idnfse_homologacao',
        'idnfse_producao',
        'centro_custo'
    ];

    static public function geraFaturamento($OrdemServicos, $centro_custo = 0)
    {
        $Cliente = ($centro_custo) ? $OrdemServicos->first()->centro_custo : $OrdemServicos->first()->cliente;
        if ($Cliente->prazo_pagamento_tecnica->id == PrazoPagamento::_STATUS_A_VISTA_) {
            $data_parcelas = ['quantidade' => 1, 'prazo' => 0];
        } else {
            $temp = $Cliente->prazo_pagamento_tecnica->extras;
            $data_parcelas = ['quantidade' => count($temp), 'prazo' => $temp];
        }

        //CRIAR PAGAMENTO
        $Pagamento = Pagamento::create();
        //ATRIBUIR IDPAGAMENTO AO FECHAMENTO
        $Faturamento = self::create([
            'idcliente' => $Cliente->idcliente,
            'idstatus_fechamento' => self::_STATUS_PAGAMENTO_PENDENTE_,
            'idpagamento' => $Pagamento->id,
            'centro_custo' => $centro_custo,
        ]);

        //SETAR ORDEM SERVIÇOS COMO FATURADAS
        foreach ($OrdemServicos as $ordem_servico) {
            $ordem_servico->setFaturamento($Faturamento->id);
        }

        $Valores = $Faturamento->getValores();
        $data = [
            'idpagamento' => $Pagamento->id,
            'idforma_pagamento' => $Cliente->idforma_pagamento_tecnica,
            'valor_parcela' => $Valores->valor_final_float / $data_parcelas['quantidade'],
        ];
        Parcela::setParcelas($data, $data_parcelas);

        return $Faturamento;
    }

    public function getValores()
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
        foreach ($this->ordem_servicos as $ordem_servico) {
            $valores = $ordem_servico->setValores();
            foreach ($valores as $key => $value) {
                $_valores[$key] += floatval($value);
            }
        }

        switch ($this->cliente->idemissao_tecnica) {
            case TipoEmissaoFaturamento::_TIPO_BOLETO_NFE_NFSE_:
                $_valores['valor_nfse_float'] = $_valores['valor_outras_despesas_float'] + $_valores['valor_total_servicos_float'];
                break;
            case TipoEmissaoFaturamento::_TIPO_BOLETO_NFSE_AGREGADO_PECA_:
                $_valores['valor_nfse_float'] = $_valores['valor_final_float'];
                break;
            default:
                $_valores['valor_nfse_float'] = 0;
                break;
        }

        foreach ($_valores as $key => $value) {
            $nkey = substr($key, 0, strlen($key) - 6);
            $_valores[$nkey] = DataHelper::getFloat2RealMoeda($value);
        }
        return (object)$_valores;
    }

    static public function remover($idfechamento)
    {
        $Fechamento = Faturamento::find($idfechamento);
        foreach ($Fechamento->ordem_servicos as $ordem_servico) {
            $ordem_servico->unsetFaturamento();
        }
        $Fechamento->pagamento->delete();
        $Fechamento->delete();
        return true;
    }

    static public function filter_layout($data)
    {
        $query = self::filter_status($data);
        return ($data['centro_custo']) ? $query->centroCustos() : $query->clientes();
    }

    static public function filter_status($data)
    {
        $data['situacao'] = (isset($data['situacao'])) ? $data['situacao'] : NULL;
        $query = self::orderBy('created_at', 'desc');
        if ($data['situacao'] != NULL) $query->where('idstatus_fechamento', $data['situacao']);
        if (isset($data['idcliente']) && ($data['idcliente'] != NULL)) $query->where('idcliente', $data['idcliente']);
//        if (isset($data['data'])) {
//            $query->where('created_at', '>=', DataHelper::getPrettyToCorrectDateTime($data['data']));
//        }
//        $User = Auth::user();
//        if ($User->hasRole('tecnico')) {
//            $query->where('idcolaborador', $User->colaborador->idcolaborador);
//        }
        return $query;
    }

    //====================== NF ===========================
    public function sendNF($debug, $type)
    {
        $ref_index = ($debug) ? Ajuste::getByMetaKey('ref_' . $type . 'index_homologacao') : Ajuste::getByMetaKey('ref_' . $type . 'index_producao');
        if ($debug) {
            $this->{'id' . $type . '_homologacao'} = $ref_index->meta_value;
        } else {
            $this->{'id' . $type . '_producao'} = $ref_index->meta_value;
        }
        $this->save();
        $ref_index->incrementa();
        return $this->setNF($debug, $type);
    }

    public function setNF($debug, $type)
    {
        if (!strcmp($type, 'nfe')) {
            $NF = new NFe($debug, $this);
        } else {
            $NF = new NFSe($debug, $this);
        }
        $retorno = $NF->emitir();

        if (isset($retorno->body->erros)) {
            $responseNF = [
                'message' => $retorno->body->erros,
                'code' => $retorno->result,
                'error' => 1,
            ];
        } else {
            $responseNF = [
                'message' => 'Nota Fiscal (Ref. #' . $NF->_REF_ . ') enviada com sucesso!',
                'code' => $retorno->result,
                'error' => 0,
            ];
        }
        return $responseNF;
    }

    public function resendNF($debug, $type)
    {
        return $this->setNF($debug, $type);
    }

    public function getNF($debug, $type)
    {
        $ref = ($debug) ? $this->{'id' . $type . '_homologacao'} : $this->{'id' . $type . '_producao'};
        return ($ref == NULL) ? $ref : json_encode(NF::consultar($ref, $debug, $type));
    }

    //====================== NFSe ===========================

    public function getStatusNFSeHomologacao()
    {
        return ($this->idnfse_homologacao != NULL);
    }

    public function getStatusNFSeProducao()
    {
        return ($this->idnfse_producao != NULL);
    }

    //====================== NFe ============================

    public function getStatusNfeHomologacao()
    {
        return ($this->idnfe_homologacao != NULL);
    }

    public function getStatusNfeProducao()
    {
        return ($this->idnfe_producao != NULL);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastCreated($query)
    {
        return $query->orderBy('created_at', 'desc')->first();;
    }

    // ******************** RELASHIONSHIP ******************************

    public function getAparelhoManutencaos()
    {
        $ids = [];
        foreach ($this->ordem_servicos as $ordem_servico) {
            $ids[] = $ordem_servico->idordem_servico;
        }
        return AparelhoManutencao::whereIn('idordem_servico', $ids)->get();
    }

    public function faturar()
    {
        $this->attributes['idstatus_fechamento'] = self::_STATUS_FATURADO_;
        foreach ($this->ordem_servicos as $ordem_servico) {
            $ordem_servico->idsituacao_ordem_servico = OrdemServico::_STATUS_FATURADA_;
            $ordem_servico->save();
        }
        return $this->save();
    }

    public function getTotalPagoReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalPago());
    }

    public function getTotalPago()
    {
        return $this->pagamento->getParcelasPagas()->sum('valor_parcela');
    }

    public function getTotalPendenteReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalPendente());
    }

    public function getTotalPendente()
    {
        return $this->pagamento->getParcelasPendentes()->sum('valor_parcela');
    }

    public function deleteAll()
    {
        foreach (self::all() as $item) {
            $item->delete();
        }
    }

    public function getPagoStatus()
    {
        return ($this->pagamento->status);
    }

    public function getPagoStatusColor()
    {
        return ($this->pagamento->status) ? 'success' : 'danger';
    }

    public function getPagoText()
    {
        return ($this->pagamento->status) ? 'Quitado' : 'Pagamento Pendente';
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getCreatedAtMonth()
    {
        return DataHelper::getPrettyDateTimeToMonth($this->attributes['created_at']);
    }

    public function getTipoFechamento()
    {
        return ($this->centro_custo == 1) ? 'Centro de Custo' : 'Cliente';
    }

    public function getStatusText()
    {
        return $this->status->descricao;
    }

    public function getStatusType()
    {
        switch ($this->attributes['idstatus_fechamento']) {
            case self::_STATUS_FATURADO_:
                return 'success';
            default:
                return 'warning';
        }
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCentroCustos($query)
    {
        return $query->where('centro_custo', 1);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClientes($query)
    {
        return $query->where('centro_custo', 0);
    }

    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\StatusFechamento', 'idstatus_fechamento');
    }

    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'idpagamento');
    }

    // ************************** HASMANY **********************************
    public function ordem_servicos()
    {
        return $this->hasMany('App\OrdemServico', 'idfechamento');
    }
}
