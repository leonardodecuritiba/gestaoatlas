<?php

namespace App\Models;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    const _STATUS_ABERTO_ = 1;
    const _STATUS_PAGO_ = 2;
    const _STATUS_PAGO_EM_ATRASO_ = 3;
    const _STATUS_PAGO_EM_CARTORIO_ = 4;
    const _STATUS_CARTORIO_ = 5;
    const _STATUS_DESCONTADO_ = 6;
    const _STATUS_VENCIDO_ = 7;
    const _STATUS_PROTESTADO_ = 8;

    public $timestamps = true; //danger
    protected $table = 'parcelas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idpagamento',
        'idstatus_parcela',
        'idforma_pagamento',
        'data_vencimento',
        'data_pagamento',
        'data_baixa',
        'numero_parcela',
        'valor_parcela',
    ];


    static public function setParcelas($data, $data_parcelas)
    {
        //CRIAR PARCELAS, ATRIBUIR ID PAGAMENTO A ELAS
        for ($i = 0; $i < $data_parcelas['quantidade']; $i++) {
            $prazo = Carbon::now()->addDay($data_parcelas['prazo'][$i]);
            Parcela::create([
                'idpagamento' => $data['idpagamento'],
                'idstatus_parcela' => self::_STATUS_ABERTO_,
                'idforma_pagamento' => $data['idforma_pagamento'],
                'data_vencimento' => $prazo->format('Y-m-d'),
                'numero_parcela' => $i + 1,
                'valor_parcela' => $data['valor_parcela']
            ]);
        }
        return true;
//        //CRIAR PARCELAS, ATRIBUIR ID PAGAMENTO A ELAS
//        for ($p = 0; $p < $cl_parcelas['quantidade']; $p++) {
//            $data = Carbon::now()->addDay($cl_parcelas['prazo'][$p]);
//            Parcela::create([
//                'idpagamento' => $Pagamento->id,
//                'idforma_pagamento' => $Cliente->idforma_pagamento_tecnica,
//                'data_vencimento' => $data->format('Y-m-d'),
//                'numero_parcela' => $p + 1,
//                'valor_parcela' => $valor_parcela
//            ]);
//        }
//        return true;
    }

    static public function getAreceber($ndays)
    {
        return self::pendentes()
            ->where('data_vencimento', '<=', Carbon::now()
                ->addDays($ndays)
                ->format('Y-m-d'))
            ->SumRealValorParcela();
    }

    static public function baixar($data)
    {
        $Parcela = self::findOrFail($data['id']);
        $Parcela->update([
            'data_pagamento' => $data['data_pagamento'],
            'data_baixa' => Carbon::now()->format('Y-m-d'),
            'idstatus_parcela' => $data['idstatus_parcela'],
        ]);
        return $Parcela;
    }

    public function recebida()
    {
        return (in_array($this->attributes['idstatus_parcela'], [
            self::_STATUS_PAGO_,
            self::_STATUS_PAGO_EM_ATRASO_,
            self::_STATUS_PAGO_EM_CARTORIO_,
            self::_STATUS_DESCONTADO_,
            self::_STATUS_PROTESTADO_,
        ]));
    }


    public function getNumeroParcela()
    {
        return $this->numero_parcela . '/' . $this->pagamento->parcelas->count();
    }

    public function getStatusText()
    {
        return $this->status->descricao;
    }

    public function getStatusColor()
    {
        switch ($this->attributes['idstatus_parcela']) {
            case self::_STATUS_ABERTO_:
                return 'warning';
            case self::_STATUS_DESCONTADO_:
            case self::_STATUS_PAGO_EM_ATRASO_:
            case self::_STATUS_CARTORIO_:
            case self::_STATUS_PROTESTADO_:
                return 'primary';
            case self::_STATUS_PAGO_:
                return 'success';
            case self::_STATUS_VENCIDO_:
                return 'danger';
        }
    }


    // ********************** Accessors ********************************

    public function valor_parcela_real()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['valor_parcela']);
    }

    public function getDataVencimentoBoleto()
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['data_vencimento']);
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getDataVencimentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataBaixaAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataPagamentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function setDataPagamentoAttribute($value)
    {
        return $this->attributes['data_pagamento'] = DataHelper::setDate($value);
    }

    public function getDataBaixaAtribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    // ********************** Scopes ***********************************
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendentes($query)
    {
        return $query->whereIn('idstatus_parcela', [
            self::_STATUS_ABERTO_,
            self::_STATUS_VENCIDO_,
            self::_STATUS_CARTORIO_,
        ]);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecebidos($query)
    {
        return $query->whereIn('idstatus_parcela', [
            self::_STATUS_PAGO_,
            self::_STATUS_PAGO_EM_ATRASO_,
            self::_STATUS_PAGO_EM_CARTORIO_,
            self::_STATUS_DESCONTADO_,
            self::_STATUS_PROTESTADO_,
        ]);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCartorios($query)
    {
        return $query->where('idstatus_parcela', self::_STATUS_CARTORIO_);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDescontados($query)
    {
        return $query->where('idstatus_parcela', self::_STATUS_DESCONTADO_);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSumRealValorParcela($query)
    {
        return DataHelper::getFloat2RealMoeda($query->sum('valor_parcela'));
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVencidos($query)
    {
        return $query->where('idstatus_parcela', self::_STATUS_VENCIDO_);
    }

    // ********************** BELONGS ********************************
    public function forma_pagamento()
    {
        return $this->belongsTo('App\FormaPagamento', 'idforma_pagamento');
    }

    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'idpagamento');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\StatusParcela', 'idstatus_parcela');
    }

    public function faturamento()
    {
        return $this->pagamento->faturamento();
    }

    public function cliente()
    {
        return $this->faturamento->cliente();
    }
    // ************************** HASMANY **********************************
}
