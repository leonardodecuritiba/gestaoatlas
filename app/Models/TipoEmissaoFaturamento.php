<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEmissaoFaturamento extends Model
{
    use SoftDeletes;
    //BOLETO, NFe, NFSe
    const _TIPO_BOLETO_NFE_NFSE_ = 1;
    const _TIPO_BOLETO_NFSE_AGREGADO_PECA_ = 2;
    const _TIPO_BOLETO_ = 3;
    public $timestamps = true;
    protected $table = 'tipo_emissao_faturamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function clientes_tecnica()
    {
        return $this->hasMany('App\Cliente', 'idemissao_tecnica');
    }

    public function clientes_comercial()
    {
        return $this->hasMany('App\Cliente', 'idemissao_comercial');
    }
}
