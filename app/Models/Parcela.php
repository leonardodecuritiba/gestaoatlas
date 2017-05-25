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


    static public function setParcelas($data, $data_parcelas)
    {
        //CRIAR PARCELAS, ATRIBUIR ID PAGAMENTO A ELAS
        for ($i = 0; $i < $data_parcelas['quantidade']; $i++) {
            $prazo = Carbon::now()->addDay($data_parcelas['prazo'][$i]);
            Parcela::create([
                'idpagamento' => $data['idpagamento'],
                'idforma_pagamento' => $data['idforma_pagamento'],
                'data_vencimento' => $prazo->format('Y-m-d'),
                'numero_parcela' => $i + 1,
                'valor_parcela' => $data['valor_parcela']
            ]);
        }
        return true;
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
        return true;
    }


    static public function pagar($data)
    {
        $Parcela = self::findOrFail($data['id']);
        $Parcela->data_pagamento = $data['data_pagamento'];
        $Parcela->data_baixa = Carbon::now()->format('Y-m-d');
        $Parcela->status = 1;
        $Parcela->save();
        return $Parcela;
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

    // ********************** BELONGS ********************************
    public function forma_pagamento()
    {
        return $this->belongsTo('App\FormaPagamento', 'idforma_pagamento');
    }

    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'idpagamento');
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
