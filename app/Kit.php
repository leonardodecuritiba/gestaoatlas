<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kit extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'kits';
    protected $primaryKey = 'idkit';
    protected $fillable = [
        'nome',
        'descricao',
        'observacao',
    ];

    public function valor_total()
    {
        $val = $this->valor_total_float();
        return number_format($val,2,',','.');
    }

    public function valor_total_float()
    {
        return $this->hasMany('App\PecaKit', 'idkit')->sum('valor_total');
    }

    // ******************** FUNCTIONS ******************************
    public function getCreatedAtAttribute($value)
    {
        if($value != NULL) return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function pecas_kit()
    {
        return $this->hasMany('App\PecaKit', 'idkit');
    }

    public function tabela_preco()
    {
        return $this->hasMany('App\TabelaPrecoKit', 'idkit');
    }

    public function kits_utilizados()
    {
        return $this->hasMany('App\KitsUtilizados', 'idkit');
    }

    public function tabela_cliente($idtabela_preco)
    {
        $tabela_preco = $this->tabela_preco_cliente($idtabela_preco)->first();
        return (count($tabela_preco) > 0) ? $tabela_preco : 0;
    }

    public function tabela_preco_cliente($idtabela_preco)
    {
        return $this->hasMany('App\TabelaPrecoKit', 'idkit')->where('idtabela_preco', $idtabela_preco);
    }
}
