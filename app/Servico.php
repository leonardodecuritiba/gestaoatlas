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
        'idgrupo',
        'idunidade',
        'nome',
        'descricao',
        'valor',
    ];


	// =====================================================================
	// ======================== NEW FUNCTIONS ==============================
	// =====================================================================

	public function getCost()
	{
		return $this->attributes['valor'];
	}

	public function getCostFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getCost());
	}

	public function getName()
	{
		return $this->getAttribute('nome');
	}

	public function getShortName()
	{
		return str_limit($this->getName(), 20);
	}

	// =====================================================================
	// =====================================================================
	// =====================================================================

	
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
    // ************************** hasOne **********************************
    public function grupo()
    {
        return $this->hasOne('App\Grupo', 'idgrupo', 'idgrupo');
    }

    public function unidade()
    {
        return $this->hasOne('App\Unidade', 'idunidade', 'idunidade');
    }

    // ************************** HASMANY **********************************
    public function tabela_preco_by_name($value)
    {
        $id = TabelaPreco::where('descricao', $value)->pluck('idtabela_preco');
        return $this->hasMany('App\TabelaPrecoServico', 'idservico')
            ->where('idtabela_preco', $id)
            ->first();
    }

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
