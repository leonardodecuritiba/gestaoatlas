<?php

namespace App\Models;

use App\AparelhoManutencao;
use App\Cliente;
use App\Helpers\DataHelper;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\VarDumper\Cloner\Data;

class Fechamento extends Model
{
    const _STATUS_FATURAMENTO_PENDENTE_ = 1;
    const _STATUS_PAGAMENTO_PENDENTE_ = 2; //danger
    const _STATUS_FATURADO_ = 3; //warning
    public $timestamps = true;//success
    protected $table = 'fechamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idcliente',
        'idstatus_fechamento',
        'idpagamento',
        'idnfe_homologacao',
        'idnfe_producao',
        'centro_custo'
    ];

    static public function geraFechamento($ordem_servicos, $centro_custo = 0)
    {
        $Cliente = $ordem_servicos[0]->cliente;
        if ($Cliente->prazo_pagamento_tecnica->id == PrazoPagamento::_STATUS_A_VISTA_) {
            $cl_parcelas = ['quantidade' => 1, 'prazo' => 0];
        } else {
            $temp = $Cliente->prazo_pagamento_tecnica->extras;
            $cl_parcelas = ['quantidade' => count($temp), 'prazo' => $temp];
        }

        //CRIAR PAGAMENTO
        $Pagamento = Pagamento::create();
        //ATRIBUIR IDPAGAMENTO AO FECHAMENTO
        $Fechamento = self::create([
            'idcliente' => $Cliente->idcliente,
            'idstatus_fechamento' => self::_STATUS_FATURAMENTO_PENDENTE_,
            'idpagamento' => $Pagamento->id,
            'centro_custo' => $centro_custo,
        ]);
        foreach ($ordem_servicos as $os) {
            $os->setFechamento($Fechamento->id);
        }

        $valores = $Fechamento->getValores();
        $valor_parcela = $valores->valor_final_float / $cl_parcelas['quantidade'];
        //CRIAR PARCELAS, ATRIBUIR ID PAGAMENTO A ELAS
        for ($p = 0; $p < $cl_parcelas['quantidade']; $p++) {
            $data = Carbon::now()->addDay($cl_parcelas['prazo'][$p]);
            Parcela::create([
                'idpagamento' => $Pagamento->id,
                'idforma_pagamento' => $Cliente->idforma_pagamento_tecnica,
                'data_vencimento' => $data->format('Y-m-d'),
                'numero_parcela' => $p + 1,
                'valor_parcela' => $valor_parcela
            ]);
        }
    }

    public function getValores()
    {
//        return $this->ordem_servicos[0]->aparelho_manutencaos[1]->servico_prestados->sum('valor');
//        return $this->ordem_servicos[0]->aparelho_manutencaos[1]->getTotalServicos();

        //substr ($string, $start, $length = null) {}
        $_valores = [
            'valor_desconto_float' => 0,
            'valor_acrescimo_float' => 0,
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
        foreach ($_valores as $key => $value) {
            $nkey = substr($key, 0, strlen($key) - 6);
            $_valores[$nkey] = DataHelper::getFloat2RealMoeda($value);
        }
        return (object)$_valores;
    }

    static public function remover($idfechamento)
    {
        $Fechamento = Fechamento::find($idfechamento);
        foreach ($Fechamento->ordem_servicos as $ordem_servico) {
            $ordem_servico->unsetFechamento();
        }
        $Fechamento->pagamento->delete();
        $Fechamento->delete();
        return true;
    }

    static public function filter_status($status)
    {
        $query = self::orderBy('created_at', 'desc');
        switch ($status) {
            case 'pendentes':
                $query->whereBetween('idstatus_fechamento', [self::_STATUS_FATURAMENTO_PENDENTE_, self::_STATUS_PAGAMENTO_PENDENTE_]);
                break;
            case 'faturados':
                $query->where('idstatus_fechamento', self::_STATUS_FATURADO_);
                break;
        }
//        $User = Auth::user();
//        if ($User->hasRole('tecnico')) {
//            $query->where('idcolaborador', $User->colaborador->idcolaborador);
//        }
        return $query;
    }

    public function getStatusNfeHomologacao()
    {
        return ($this->idnfe_homologacao != NULL);
    }

    public function getStatusNfeProducao()
    {
        return ($this->idnfe_producao != NULL);
    }

    public function setNfe($debug = true)
    {
        if ($debug) {
            $this->idnfe_homologacao = $this->id + 9;
        } else {
            $this->idnfe_producao = $this->id;
        }
        $this->save();
        $NFE = new Nfe($debug, $this);
        $retorno = $NFE->envia();

        if (isset($retorno->body->erros)) {
            $responseNFE = [
                'message' => $retorno->body->erros,
                'code' => $retorno->result,
                'error' => 1,
            ];
        } else {
            $responseNFE = [
                'message' => 'Nota Fiscal (#' . $NFE->ref . ') gerada com sucesso!',
                'code' => $retorno->result,
                'error' => 0,
            ];
        }
        return $responseNFE;
    }

    public function getDataNfe($debug = true)
    {
        $ref = ($debug) ? $this->idnfe_homologacao : $this->idnfe_producao;
        if ($ref == NULL) RETURN $ref;
        return json_encode(Nfe::consulta($ref, $debug));
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