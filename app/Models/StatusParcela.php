<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusParcela extends Model
{
    const _STATUS_ABERTO_ = 1;
    const _STATUS_PAGO_ = 2;
    const _STATUS_PAGO_EM_ATRASO_ = 3;
    const _STATUS_PAGO_EM_CARTORIO_ = 4;
    const _STATUS_CARTORIO_ = 5;
    const _STATUS_DESCONTADO_ = 6;
    const _STATUS_VENCIDO_ = 7;
    const _STATUS_PROTESTADO_ = 8;

    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'status_parcelas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function parcelas()
    {
        return $this->hasMany('App\Models\Parcela', 'idstatus_parcela');
    }
}
