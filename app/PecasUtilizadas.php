<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecasUtilizadas extends Model
{
    use SoftDeletes;
    protected $table = 'pecas_utilizadas';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'idaparelho_manutencao',
        'idpeca',
        'valor',
    ];

    // ******************** FUNCTIONS ******************************
    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = DataHelper::getReal2Float($value);
    }
    public function getValorAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }
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
