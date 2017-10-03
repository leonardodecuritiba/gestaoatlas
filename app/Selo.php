<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacre;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selo extends Model
{
    use SeloLacre;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'selos';
    protected $primaryKey = 'idselo';
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
        'used',
    ];


    // ******************** FUNCTIONS ******************************

    static public function assign($data)
    {
        return self::whereIn('idselo', $data['valores'])
            ->update(['idtecnico' => $data['idtecnico']]);
    }

    static public function selo_exists($numeracao)
    {
        return (self::where('numeracao', $numeracao)->count() > 0);
    }

    public function scopeNumeracao($query, $numeracao)
    {
        return $query->where('numeracao', 'like', '%' . $numeracao . '%')
            ->orWhere('numeracao_externa', 'like', '%' . $numeracao . '%');
    }

    public function getFormatedSelo()
    {
        if ($this->isExterno()) {
            $cod = $this->attributes['numeracao_externa'];
            return ($cod != NULL) ? $cod : '-';
        }
        $cod = $this->attributes['numeracao'];
        return ($cod != NULL) ? DataHelper::mask($cod, '##.###.###') : '-';

    }

    public function isExterno()
    {
        return ($this->attributes['numeracao'] == NULL);
    }

	public function getOrdemServicoID()
	{
		$selo_i = $this->selo_instrumento;
		if($selo_i!=NULL){
			return $selo_i->aparelho_set->idordem_servico;
		}
		return $selo_i;
	}

    public function getFormatedSeloDV()
    {
        if ($this->isExterno()) {
            $cod = $this->attributes['numeracao_externa'];
            return ($cod != NULL) ? $cod : '-';
        }
        $cod = ($this->attributes['numeracao'] != NULL) ? $this->attributes['numeracao'] . $this->getDV() : NULL;
        return ($cod != NULL) ? DataHelper::mask($cod, '##.###.###-#') : '-';
    }

    static public function getAllListagem($filters)
    {
    	$selo = new self();
	    $selos_query = $selo->newQuery();
    	if(isset($filters['numeracao'])){
		    $filters['numeracao'] = DataHelper::getOnlyNumbers($filters['numeracao']);
		    $selos_query->where('numeracao', 'like','%' .$filters['numeracao']. '%');
	    }
    	if(isset($filters['origem'])){
		    $selos_query->where('idtecnico', $filters['origem']);
	    }
//    	if(isset($filters['cnpj'])){
//		    $filters['cnpj'] = DataHelper::getOnlyNumbers($filters['cnpj']);
//    		return $p = PessoaJuridica::where('cnpj', $filters['cnpj'])->get();
//		    $selos_query->where('idtecnico', $filters['origem']);
//	    }

        return $selos_query->get()->map(function($s){
        	$selo_instrumento = $s->selo_instrumento;

	        if($selo_instrumento!=NULL){
	        	$instrumento = $selo_instrumento->instrumento;
	        	$cliente = $instrumento->cliente->getType();
		        $s->id_os               = $selo_instrumento->aparelho_set->idordem_servico;
		        $s->n_serie             = $instrumento->numero_serie;
		        $s->n_inventario        = $instrumento->inventario;
		        $s->cliente_documento   = $cliente->documento;
	        } else {
		        $s->id_os               = '-';
		        $s->n_serie             = '-';
		        $s->n_inventario        = '-';
		        $s->cnpj                = '-';
		        $s->cliente_documento   = '-';
	        }

        	$s->nome_tecnico    = $s->getNomeTecnico();
        	$s->selo_formatado  = $s->getFormatedSeloDV();
        	$s->status_color    = $s->getStatusColor();
        	$s->status_text     = $s->getStatusText();
        	return $s;
        });
    }

    public function getDV()
    {
        return DataHelper::calculateModulo11($this->numeracao);
    }

    public function has_selo_instrumento()
    {
        return ($this->selo_instrumento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function selo_instrumento()
    {
        return $this->hasOne('App\SeloInstrumento', 'idselo');
    }

    // ********************** HASONE ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ************************** HASMANY **********************************
}
