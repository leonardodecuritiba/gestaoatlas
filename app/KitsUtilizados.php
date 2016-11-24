<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KitsUtilizados extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'kits_utilizados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idaparelho_manutencao',
        'idkit',
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
    public function nome()
    {
        return $this->kit->nome;
    }
    public function valor_original()
    {
        return $this->kit->valor_total();
    }

    public function valor_float()
    {
        return $this->attributes['valor'];
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
