<?php

namespace App\Models;

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

    // ********************** BELONGS ********************************
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
