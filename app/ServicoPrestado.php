<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Valores;

class ServicoPrestado extends Model
{
//    use SoftDeletes;
    use Valores;
    public $timestamps = true;
    public $total;
    protected $table = 'servico_prestados';
    protected $primaryKey = 'idservico_prestado';
    protected $fillable = [
        'idaparelho_manutencao',
        'idservico',
        'valor',
        'quantidade',
        'desconto'
    ];

    // ******************** FUNCTIONS ******************************

    public function valor_original()
    {
        return $this->servico->valor;
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function aparelho_manutencao()
    {
        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
    }
    public function servico()
    {
        return $this->belongsTo('App\Servico', 'idservico');
    }
    // ************************** HASMANY **********************************
}
