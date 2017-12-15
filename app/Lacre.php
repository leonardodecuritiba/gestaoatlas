<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Lacre extends Model
{
    use SeloLacre;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'lacres';
    protected $primaryKey = 'idlacre';
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
        'used',
    ];

    // ******************** FUNCTIONS ******************************

	static public function getAllListagem($filters)
	{
		$self = new self();
		$query = $self->newQuery();
		if(isset($filters['numeracao'])){
			$filters['numeracao'] = DataHelper::getOnlyNumbers($filters['numeracao']);
			$query->where('numeracao', 'like','%' .$filters['numeracao']. '%');
		}
		if(isset($filters['origem'])){
			if($filters['origem']>0){
				$query->where('idtecnico', $filters['origem']);
			}
		}
    	if(isset($filters['cnpj'])){
		    $filters['cnpj'] = DataHelper::getOnlyNumbers($filters['cnpj']);
//		    $idspjuridica = PessoaJuridica::where('cnpj', 'like','%' .$filters['cnpj']. '%')->pluck('idpjuridica');
//		    $idsclientes = Cliente::whereIn("idpjuridica",$idspjuridica)->pluck('idcliente');
//		    $idsos = OrdemServico::whereIn("idcliente",$idsclientes)->pluck('idordem_servico');
//		    $idaparelho_manutencao = AparelhoManutencao::whereIn("idordem_servico",$idsos)->pluck('idaparelho_manutencao');
//		    $ids = LacreInstrumento::whereIn('idaparelho_set', $idaparelho_manutencao)->orWhereIn('idaparelho_unset', $idaparelho_manutencao)->pluck('idlacre');
//		    $query->whereIn('idlacre', $ids);

		    $ids = DB::table('lacre_instrumentos')
		             ->join('aparelho_manutencaos', function ($join) {
			             $join->on('aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set')
			                  ->orOn('aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set');
		             })
		               ->join('ordem_servicos'      , 'ordem_servicos.idordem_servico', '='             , 'aparelho_manutencaos.idordem_servico')
		               ->join('clientes'            , 'clientes.idcliente', '='                         , 'ordem_servicos.idcliente')
		               ->join('pjuridicas'          , 'pjuridicas.idpjuridica', '='                     , 'clientes.idpjuridica')
		               ->select('lacre_instrumentos.idlacre')
		               ->where('pjuridicas.cnpj', 'like','%' .$filters['cnpj']. '%')
			            ->pluck('idlacre');
		    $query->whereIn('idlacre', $ids);
	    }

		if(isset($filters['idordem_servico'])){
			if($filters['idordem_servico']!='') {
				$ids             = DB::table( 'lacre_instrumentos' )
				                     ->join( 'aparelho_manutencaos', function ( $join ) {
					                     $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' )
					                          ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' );
				                     })
				                     ->where('aparelho_manutencaos.idordem_servico',$filters['idordem_servico'])
				                     ->pluck( 'idlacre' );
				$query->whereIn( 'idlacre', $ids );
			}
		}

		if(isset($filters['numero_serie'])){
			if($filters['numero_serie']!='') {
				$filters['numero_serie'] = DataHelper::getOnlyNumbers($filters['numero_serie']);
				$ids             = DB::table( 'lacre_instrumentos' )
				                     ->join( 'aparelho_manutencaos', function ( $join ) {
					                     $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' )
					                          ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' );
				                     })
				                     ->join('instrumentos' , 'aparelho_manutencaos.idinstrumento', '=', 'instrumentos.idinstrumento')
				                     ->where('instrumentos.numero_serie', 'like','%' .$filters['numero_serie']. '%')
				                     ->pluck( 'idlacre' );
				$query->whereIn( 'idlacre', $ids );
			}
		}

		if(isset($filters['inventario'])){
			if($filters['inventario']!='') {
				$filters['inventario'] = DataHelper::getOnlyNumbers($filters['inventario']);
				$ids             = DB::table( 'lacre_instrumentos' )
				                     ->join( 'aparelho_manutencaos', function ( $join ) {
					                     $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' )
					                          ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'lacre_instrumentos.idaparelho_set' );
				                     })
				                     ->join('instrumentos' , 'aparelho_manutencaos.idinstrumento', '=', 'instrumentos.idinstrumento')
				                     ->where('instrumentos.inventario', 'like','%' .$filters['inventario']. '%')
				                     ->pluck( 'idlacre' );
				$query->whereIn( 'idlacre', $ids );
			}
		}


		return $query->get()->map(function($s){
			$x_instrumento = $s->lacre_instrumento;

			if($x_instrumento!=NULL){
				$instrumento = $x_instrumento->instrumento;
				$cliente = $instrumento->cliente->getType();

				$s->idos_set            = ($x_instrumento->idaparelho_set != NULL) ? $x_instrumento->aparelho_set->idordem_servico : NULL;
				$s->idos_unset          = ($x_instrumento->idaparelho_unset != NULL) ? $x_instrumento->aparelho_unset->idordem_servico : NULL;
				$s->n_serie             = $instrumento->numero_serie;
				$s->n_inventario        = $instrumento->inventario;
				$s->cliente_documento   = $cliente->documento;
			}

			$s->nome_tecnico    = $s->getNomeTecnico();
			$s->numero_formatado= $s->numeracao;
			$s->status_color    = $s->getStatusColor();
			$s->status_text     = $s->getStatusText();
			return $s;
		});
	}

    static public function assign($data)
    {
        return self::whereIn('idlacre', $data['valores'])
            ->update(['idtecnico' => $data['idtecnico']]);
    }

	public function isExterno()
	{
		return (($this->attributes['numeracao_externa'] != NULL) && ($this->attributes['numeracao'] == NULL));
	}

    static public function lacre_exists($numeracao)
    {
        return self::where('numeracao', $numeracao)->exists();
    }

    public function has_lacre_instrumento()
    {
        return ($this->lacre_instrumento()->count() > 0);
    }

    public function lacre_instrumento()
    {
        return $this->hasOne('App\LacreInstrumento', 'idlacre');
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function getNumeracao()
    {
        return ($this->isExterno()) ? $this->attributes['numeracao_externa'] : $this->attributes['numeracao'];
    }

    // ********************** HASONE ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ************************** HASMANY **********************************
}
