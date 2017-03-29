<?php

namespace App\Models;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    const _STATUS_PAGO_ = 1;
    const _STATUS_PENDENTE_ = 0; //danger
    public $timestamps = true; //danger
    protected $table = 'parcelas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idpagamento',
        'idforma_pagamento',
        'data_vencimento',
        'data_pagamento',
        'data_baixa',
        'numero_parcela',
        'valor_parcela',
        'status',
    ];


    public function getDataVencimentoBoleto()
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['data_vencimento']);
    }

    public function getNumeroParcela()
    {
        return $this->numero_parcela . '/' . $this->pagamento->parcelas->count();
    }

    public function getStatusText()
    {
        return ($this->status) ? 'Pago' : 'Pendente';
    }

    public function getStatusColor()
    {
        return ($this->status) ? 'success' : 'danger';
    }

    public function valor_parcela_real()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['valor_parcela']);
    }

    // ********************** Accessors ********************************
    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getDataVencimentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataPagamentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getDataBaixaAtribute($value)
    {
        return DataHelper::getPrettyDate($value);
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

    public function fechamento()
    {
        return $this->pagamento->fechamento();
    }

    public function cliente()
    {
        return $this->fechamento->cliente();
    }
    // ************************** HASMANY **********************************
}
