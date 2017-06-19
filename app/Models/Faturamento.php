<?php

namespace App\Models;

use App\Models\NotasFiscais\NF;
use App\Models\NotasFiscais\NFe;
use App\Models\NotasFiscais\NFSe;

use App\PecasUtilizadas;
use App\Scopes\LastCreatedScope;
use App\Ajuste;
use App\AparelhoManutencao;
use App\Helpers\DataHelper;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Faturamento extends Model
{
//    const _STATUS_FATURAMENTO_PENDENTE_ = 1;
    const _STATUS_ABERTO_ = 1;//danger
    const _STATUS_FINALIZADO_ = 2; //success
    const _STATUS_QUITADO_ = 3; //success
    public $timestamps = true;
    protected $table = 'faturamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idcliente',
        'idstatus_faturamento',
        'idpagamento',
        'idnfe_homologacao',
        'data_nfe_homologacao',
        'idnfe_producao',
        'data_nfe_producao',
        'idnfse_homologacao',
        'data_nfse_homologacao',
        'idnfse_producao',
        'data_nfse_producao',
        'centro_custo'
    ];

    //================== FUNCTIONS ========================

    static public function faturaPeriodo($OrdemServicos)
    {
        $faturamento_cc = []; //faturamento centro de custos
        $faturamento_cl = []; //faturamento clientes
        foreach ($OrdemServicos as $ordem_servico) {
            if ($ordem_servico->idcentro_custo != NULL) {
                $idcentro_custo = $ordem_servico->idcentro_custo;
                $faturamento_cc[$idcentro_custo][] = $ordem_servico;
            } else {
                $idcliente = $ordem_servico->idcliente;
                $faturamento_cl[$idcliente][] = $ordem_servico;
            }
        }

        //faturamentos CLIENTES
        foreach ($faturamento_cl as $ordem_servicos) {
            Faturamento::geraFaturamento($ordem_servicos, 0, $op = 1);
        }

        //faturamentos CENTRO DE CUSTO
        foreach ($faturamento_cc as $ordem_servicos) {
            Faturamento::geraFaturamento($ordem_servicos, 1, $op = 1);
        }

        return Faturamento::lastCreated()->first();

    }

    static public function geraFaturamento($OrdemServicos, $centro_custo = 0, $arr = 0)
    {
        if ($arr == 1) {
            $Cliente = ($centro_custo) ? $OrdemServicos[0]->centro_custo : $OrdemServicos[0]->cliente;
        } else {
            $Cliente = ($centro_custo) ? $OrdemServicos->first()->centro_custo : $OrdemServicos->first()->cliente;
        }
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
            'idstatus_faturamento' => self::_STATUS_ABERTO_,
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

    static public function fechar($id)
    {
        $Faturamento = self::find($id);
        return $Faturamento->update([
            'idstatus_faturamento' => self::_STATUS_FINALIZADO_
        ]);
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
        return (isset($data['centro_custo']) && $data['centro_custo']) ? $query->centroCustos() : $query->clientes();
    }

    static public function filter_status($data)
    {
        $data['situacao'] = (isset($data['situacao'])) ? $data['situacao'] : NULL;
        $query = self::orderBy('created_at', 'desc');
        if ($data['situacao'] != NULL) $query->where('idstatus_faturamento', $data['situacao']);
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

    public function faturar()
    {
        $this->attributes['idstatus_faturamento'] = self::_STATUS_FINALIZADO_;
        foreach ($this->ordem_servicos as $ordem_servico) {
            $ordem_servico->idsituacao_ordem_servico = OrdemServico::_STATUS_FATURADA_;
            $ordem_servico->save();
        }
        return $this->save();
    }

    public function isAberto()
    {
        return ($this->attributes['idstatus_faturamento'] == self::_STATUS_ABERTO_);
    }


    //====================== NF ===========================

    public function sendNfByEmail($link)
    {
        return $this->cliente->sendNF($link);
    }

    public function cancelNF($debug, $type, $data)
    {
        $debug = ($debug) ? 'homologacao' : 'producao';
        $ref_key = 'id' . $type . '_' . $debug;
        return NF::cancelar($this->{$ref_key}, $debug, $type, $data);
    }

    public function setUrl($type, $link)
    {
//        link_nfe
//        link_nfse
        return $this->update(['link_' . $type => $link]);
    }

    public function sendNF($debug, $type)
    {
        $option = ($debug) ? 'homologacao' : 'producao';
        $ref_key = $type . '_' . $option;
        $ref_index = Ajuste::getByMetaKey('ref_' . $type . 'index_' . $option);
//        $this->{'id' . $type . '_' . $option} = $ref_index->meta_value;
        $this->update([
            'id' . $ref_key => $ref_index->meta_value,
            'data' . $ref_key => Carbon::now()
        ]);
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
        return ($this->attributes['idnfse_homologacao'] != NULL);
    }

    public function getStatusNFSeProducao()
    {
        return ($this->attributes['idnfse_producao'] != NULL);
    }

    //====================== NFe ============================

    public function getStatusNfeHomologacao()
    {
        return ($this->attributes['idnfe_homologacao'] != NULL);
    }

    public function getStatusNfeProducao()
    {
        return ($this->attributes['idnfe_producao'] != NULL);
    }


    // ******************** RELASHIONSHIP ******************************

    public function getAllPecas()
    {
        $ids_aparelhos_manutencao = AparelhoManutencao::whereIn('idordem_servico', $this->ordem_servicos->pluck('idordem_servico'))
            ->pluck('idaparelho_manutencao');
        return PecasUtilizadas::whereIn('idaparelho_manutencao', $ids_aparelhos_manutencao)
            ->with('peca')
            ->groupBy('idpeca')
            ->select('*', DB::raw('SUM(quantidade) as quantidade_comercial'))
            ->get();
    }

    public function getAparelhoManutencaos()
    {
        return AparelhoManutencao::whereIn('idordem_servico', $this->ordem_servicos->pluck('idordem_servico'))->get();
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

    public function getTipoFaturamento()
    {
        return ($this->centro_custo == 1) ? 'Centro de Custo' : 'Cliente';
    }

    public function getDataNF($tipo)
    {
        return DataHelper::getPrettyDateTime($this->attributes[$tipo]);
    }

    public function getStatusText()
    {
        return $this->status->descricao;
    }

    public function getStatusType()
    {
        switch ($this->attributes['idstatus_faturamento']) {
            case self::_STATUS_ABERTO_:
                return 'warning';
            case self::_STATUS_FINALIZADO_:
                return 'danger';
            case self::_STATUS_QUITADO_:
                return 'success';
        }
    }

    // ************************ SCOPE ********************************

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAbertos($query)
    {
        return $query->where('idstatus_faturamento', self::_STATUS_ABERTO_)->orderBy('id');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinalizados($query)
    {
        return $query->where('idstatus_faturamento', self::_STATUS_FINALIZADO_)->orderBy('id');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQuitados($query)
    {
        return $query->where('idstatus_faturamento', self::_STATUS_QUITADO_)->orderBy('id');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastCreated($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
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
        return $this->belongsTo('App\Models\StatusFechamento', 'idstatus_faturamento');
    }

    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'idpagamento');
    }

    // ************************** HASMANY **********************************
    public function ordem_servicos()
    {
        return $this->hasMany('App\OrdemServico', 'idfaturamento');
    }
}
