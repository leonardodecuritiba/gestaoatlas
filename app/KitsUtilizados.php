<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Valores;

class KitsUtilizados extends Model
{
    use Valores;
    public $timestamps = true;
    protected $table = 'kits_utilizados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idaparelho_manutencao',
        'idkit',
        'valor',
        'quantidade',
        'desconto'
    ];

    // ******************** FUNCTIONS ******************************
    public function nome()
    {
        return $this->kit->nome;
    }
    public function valor_original()
    {
        return $this->kit->valor_total();
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function aparelho_manutencao()
    {
        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
    }
    public function kit()
    {
        return $this->belongsTo('App\Kit', 'idkit');
    }
    // ************************** HASMANY **********************************
}
