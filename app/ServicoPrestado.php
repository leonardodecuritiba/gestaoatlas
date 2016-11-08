<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicoPrestado extends Model
{
    use SoftDeletes;
    protected $table = 'servico_prestados';
    protected $primaryKey = 'idservico_prestado';
    public $timestamps = true;
    protected $fillable = [
        'idaparelho_manutencao',
        'idservico',
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
