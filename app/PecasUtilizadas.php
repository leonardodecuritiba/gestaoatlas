<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecasUtilizadas extends Model
{
//    use SoftDeletes;
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
    public function getValorAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
    public function valor_original()
    {
        return $this->peca->custo_final;
    }

    public function valor_total_real()
    {
        return 'R$ ' . DataHelper::getFloat2Real($this->valor_total());
    }

    public function valor_total()
    {
        return $this->attributes['valor'] * $this->attributes['quantidade'];
    }

    public function valor_float()
    {
        return $this->getValorFloatAttribute();
    }

    public function getValorFloatAttribute()
    {
        return $this->attributes['valor'];
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
