<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'servicos';
    protected $primaryKey = 'idservico';
    protected $fillable = [
        'nome',
        'descricao',
        'valor',
    ];

    public function getCreatedAtAttribute($value)
    {
        return ($value==NULL)?$value:Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y - H:i');
    }
    public function getValorAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = DataHelper::getReal2Float($value);
    }

    public function valor_float()
    {
        return $this->attributes['valor'];
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    // ************************** HASMANY **********************************
    public function servico_prestados()
    {
        return $this->hasMany('App\ServicoPrestado', 'idservico');
    }

    public function tabela_preco()
    {
        return $this->hasMany('App\TabelaPrecoServico', 'idservico');
    }

    public function tabela_cliente($idtabela_preco)
    {
        $tabela_preco = $this->tabela_preco_cliente($idtabela_preco)->first();
        return (count($tabela_preco) > 0) ? $tabela_preco : 0;
    }

    public function tabela_preco_cliente($idtabela_preco)
    {
        return $this->hasMany('App\TabelaPrecoServico', 'idservico')->where('idtabela_preco', $idtabela_preco);
    }
}
