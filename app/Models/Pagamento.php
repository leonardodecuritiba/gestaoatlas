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
        $Parcela = Parcela::baixar($data);
        $Pagamento = $Parcela->pagamento;
        if ($Pagamento->getParcelasPendentes()->count() == 0) {
            $Pagamento->data_baixa = Carbon::now()->format('Y-m-d');
            $Pagamento->status = 1;
            $Pagamento->save();
            $Pagamento->faturamento->faturar();
        }
        return $Pagamento;
    }


    // ********************** BELONGS ********************************
    public function getParcelasPendentes()
    {
        return Parcela::where('idpagamento', $this->id)->pendentes();
    }

    public function getParcelasPagas()
    {
        return Parcela::where('idpagamento', $this->id)->recebidos();
    }

    // ************************** HASONE **********************************
    public function faturamento()
    {
        return $this->hasOne('App\Models\Faturamento', 'idpagamento');
    }

    // ************************** HASMANY **********************************
    public function parcelas()
    {
        return $this->hasMany('App\Models\Parcela', 'idpagamento');
    }
}
