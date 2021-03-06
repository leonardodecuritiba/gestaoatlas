<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPagamento extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'formas_pagamentos';
    protected $primaryKey = 'idforma_pagamento';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    // ************************** HASMANY **********************************
    public function clientes_comercial()
    {
        return $this->hasMany('App\Cliente', 'idforma_pagamento_comercial');
    }

    public function clientes_tecnica()
    {
        return $this->hasMany('App\Cliente', 'idforma_pagamento_tecnica');
    }
}
