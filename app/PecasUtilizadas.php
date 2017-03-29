<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Valores;

class PecasUtilizadas extends Model
{
//    use SoftDeletes;
    use Valores;
    public $timestamps = true;
    protected $table = 'pecas_utilizadas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idaparelho_manutencao',
        'idpeca',
        'valor',
        'quantidade',
        'desconto'
    ];

    // ******************** FUNCTIONS ******************************
    public function valor_original()
    {
        return $this->peca->custo_final;
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function aparelho_manutencao()
    {
        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
    }
    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idpeca');
    }
    // ************************** HASMANY **********************************
}
