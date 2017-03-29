<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    const _STATUS_PAGO_ = 1;
    const _STATUS_PENDENTE_ = 0; //danger
    public $timestamps = true; //danger
    protected $table = 'pagamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_baixa',
        'status',
    ];

    static public function baixaParcela($data)
    {
        $Parcela = Parcela::pagar($data);
        $Pagamento = $Parcela->pagamento;
        if ($Pagamento->getParcelasPendentes()->count() == 0) {
            $Pagamento->data_baixa = Carbon::now()->format('Y-m-d');
            $Pagamento->status = 1;
            $Pagamento->save();
            $Pagamento->fechamento->faturar();
        }
        return $Pagamento;
    }

    // ********************** BELONGS ********************************
    public function getParcelasPendentes()
    {
        return $this->parcelas->where('status', 0);
    }

    public function getParcelasPagas()
    {
        return $this->parcelas->where('status', 1);
    }

    // ************************** HASONE **********************************
    public function fechamento()
    {
        return $this->hasOne('App\Models\Fechamento', 'idpagamento');
    }

    // ************************** HASMANY **********************************
    public function parcelas()
    {
        return $this->hasMany('App\Models\Parcela', 'idpagamento');
    }
}
