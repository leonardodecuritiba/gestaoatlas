<?php

namespace App;

use App\Helpers\ImageHelper;
use App\Traits\InstrumentsTrait;
use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
	use InstrumentsTrait;
	public $timestamps = true;
    protected $table = 'instrumentos';
    protected $primaryKey = 'idinstrumento';
    protected $fillable = [
        'idcliente',
        'numero_serie',
        'inventario',
        'patrimonio',
        'ano',
        'ip',
        'endereco',
        'idbase',
        'idsetor',
        'idprotecao',

        'etiqueta_identificacao',
        'etiqueta_inventario'
//        'idmarca',-
//        'idcolaborador_criador',-
//        'idcolaborador_validador',-
//        'validated_at',-
//        'descricao',-
//        'foto'-
//        'modelo',-
//        'portaria',-
//        'divisao',-
//        'capacidade',-
//        'setor',-
    ];


	// ************************** HAS **********************************
	public function has_aparelho_manutencao() {
		return ( $this->aparelho_manutencao()->count() > 0 );
	}

	public function has_selo_instrumentos_fixado() {
		return ( $this->selo_instrumentos_afixado()->count() > 0 );
	}

	public function has_selo_instrumentos_retirado() {
		return ( $this->selo_instrumentos_retirado()->count() > 0 );
	}

	public function has_lacres_instrumentos_afixados() {
		return ( $this->lacres_instrumentos_afixados()->count() > 0 );
	}

	public function has_lacres_instrumentos_retirados() {
		return ( $this->lacres_instrumentos_retirados()->count() > 0 );
	}

	// ******************** FUNCTIONS ****************************

    //SELOS --------
	public function selo_retirado($idaparelho_unset = NULL) {
		if ( $this->selo_instrumentos_retirado($idaparelho_unset)->count() > 0 ) {
			return $this->selo_instrumentos_retirado($idaparelho_unset)->get();
		}
		return null;
	}

	public function selo_afixado($idaparelho_set = NULL)
	{
		return $this->selo_instrumentos_afixado($idaparelho_set)->get();
	}

	public function numeracao_selo_afixado($idaparelho_set = NULL)
	{
		$selos = $this->selo_afixado($idaparelho_set);
		$num_selos = count($selos);
		if($num_selos == 0){
			$numeracoes['text'] = 'Sem intervenção';
			$numeracoes['id'] = NULL;
			$numeracoes['declared'] = NULL;
		} else if($num_selos > 1){
			$numeracoes = array();
			foreach($selos as $selo_instrumento){
				$numeracoes['text'][] = $selo_instrumento->selo->getFormatedSeloDV();
				$numeracoes['id'][] = $selo_instrumento->idselo_instrumento;
				$numeracoes['declared'][] = $selo_instrumento->selo->declared;
			}
			$numeracoes['text'] = implode('; ',$numeracoes['text']);
		} else {
			$numeracoes['text'] = $selos->first()->selo->getFormatedSeloDV();
			$numeracoes['id'] = $selos->first()->idselo_instrumento;
			$numeracoes['declared'] = $selos->first()->declared;
		}
		return $numeracoes;

	}

	public function numeracao_selo_retirado($idaparelho_unset = NULL) {
		$selos = $this->selo_retirado($idaparelho_unset);
		$num_selos = count($selos);
		if($num_selos == 0){
			$numeracoes['text'] = 'Sem intervenção';
		} else if($num_selos > 1){
			$numeracoes = array();
			foreach($selos as $selo_instrumento){
				$numeracoes['text'][] = $selo_instrumento->selo->getFormatedSeloDV();
				$numeracoes['id'][] = $selo_instrumento->idselo_instrumento;
			}
			$numeracoes['text'] = implode('; ',$numeracoes['text']);
		} else {
			$numeracoes['text'] = $selos->first()->selo->getFormatedSeloDV();
			$numeracoes['id'] = $selos->first()->idselo_instrumento;
		}
		return $numeracoes;
	}

	public function selo_instrumento_cliente() {
		$selosInstrumento = $this->selo_instrumentos;
		if ( $selosInstrumento->count() > 0 ) {
			return $selosInstrumento->map( function ( $s ) {
				$s->nome_tecnico = $s->selo->getNomeTecnico();
				$s->retirado_em  = $s->getUnsetText();
				$s->afixado_em   = $s->getSetText();
				$s->numeracao_dv = $s->selo->getFormatedSeloDV();

				return $s;
			} );
		}
		return null;
	}

	//LACRES --------

	public function lacres_retirados($idaparelho_unset = NULL) {
		return $this->lacres_instrumentos_retirados($idaparelho_unset)->get();
	}

	public function lacres_afixados($idaparelho_set = NULL)
	{
		return $this->lacres_instrumentos_afixados($idaparelho_set)->get();
	}

    public function numeracao_lacres_afixados($idaparelho_set = NULL)
    {
        $lacres = $this->lacres_afixados($idaparelho_set);
	    $num_lacres = count($lacres);
	    if($num_lacres == 0){
		    $numeracoes['text'] = 'Sem intervenção';
		    $numeracoes['id'] = NULL;
	    } else if($num_lacres > 1){
		    $numeracoes = array();
		    foreach($lacres as $lacre_instrumento){
			    $num = $lacre_instrumento->lacre->getNumeracao();
			    $id = $lacre_instrumento->idlacre_instrumento;

			    $numeracoes['text'][] = $num;
			    $numeracoes['id'][] = $id;
			    $numeracoes['list'][] = [
				    'id'    => $id,
				    'text'  => $num,
			    ];
		    }
		    $numeracoes['text'] = implode('; ',$numeracoes['text']);
	    } else {
		    $num = $lacres->first()->lacre->getNumeracao();
		    $id = $lacres->first()->idlacre_instrumento;
		    $numeracoes['text'] = $num;
		    $numeracoes['id'] = $id;
		    $numeracoes['list'][] = [
			    'id'    => $id,
			    'text'  => $num,
		    ];
	    }
	    return $numeracoes;
    }

    public function numeracao_lacres_retirados($idaparelho_unset = NULL)
    {
	    $lacres = $this->lacres_retirados($idaparelho_unset);
	    $num_lacres = count($lacres);
	    if($num_lacres == 0){
		    $numeracoes['text'] = 'Sem intervenção';
		    $numeracoes['id'] = NULL;
	    } else if($num_lacres > 1){
		    $numeracoes = array();
		    foreach($lacres as $lacre_instrumento){
			    $num = $lacre_instrumento->lacre->getNumeracao();
			    $id = $lacre_instrumento->idlacre_instrumento;

			    $numeracoes['text'][] = $num;
			    $numeracoes['id'][] = $id;
			    $numeracoes['list'][] = [
				    'id'    => $id,
				    'text'  => $num,
			    ];
		    }
		    $numeracoes['text'] = implode('; ',$numeracoes['text']);
	    } else {
		    $num = $lacres->first()->lacre->getNumeracao();
		    $id = $lacres->first()->idlacre_instrumento;
		    $numeracoes['text'] = $num;
		    $numeracoes['id'] = $id;
		    $numeracoes['list'][] = [
			    'id'    => $id,
			    'text'  => $num,
		    ];
	    }
	    return $numeracoes;
    }

    public function lacres_instrumento_cliente()
    {
        $lacresInstrumento = $this->lacres_instrumentos;
        if ($lacresInstrumento->count() > 0) {
            return $lacresInstrumento->map(function ($l) {
                $l->nome_tecnico = $l->lacre->getNomeTecnico();
	            $l->retirado_em  = $l->getUnsetText();
	            $l->afixado_em   = $l->getSetText();
                $l->numeracao    = $l->lacre->getNumeracao();
                return $l;
            });
        }
        return NULL;
    }

	// ******************** RELASHIONSHIP ******************************



	public function selo_instrumentos_retirado($idaparelho_unset = NULL) {
		$o = $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )
					->where('external',0)
		            ->whereNotNull( 'idaparelho_unset' )
		;
		if($idaparelho_unset != NULL){
			$o->where('idaparelho_unset', $idaparelho_unset);
		}
		return $o->orderBy( 'retirado_em' , 'DESC');
	}

	public function selo_instrumentos_afixado($idaparelho_set = NULL) {
		$o = $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )->where('external',0);
		if($idaparelho_set != NULL){
			$o->where('idaparelho_set', $idaparelho_set);
		}
		else {
			$o->whereNull('idaparelho_unset');
		}
//		$o->whereNull('idaparelho_unset');
		return $o->orderBy( 'retirado_em' , 'ASC');
	}

	public function lacres_instrumentos_retirados($idaparelho_unset = NULL) {
		$o = $this->hasMany( 'App\LacreInstrumento', 'idinstrumento', 'idinstrumento' )
		          ->whereNotNull( 'idaparelho_unset' )->orderBy( 'retirado_em', 'DESC' );
		if($idaparelho_unset != NULL){
			$o->where('idaparelho_unset', $idaparelho_unset);
		}
		return $o;
	}

	public function lacres_instrumentos_afixados($idaparelho_set = NULL) {
		$o = $this->hasMany( 'App\LacreInstrumento', 'idinstrumento' )->where('external',0);
		if($idaparelho_set != NULL){
			$o->where('idaparelho_set', $idaparelho_set)
			  ->where('external',0);
		} else {
			$o->whereNull('idaparelho_unset');
		}
		return $o->orderBy( 'retirado_em' , 'ASC');
	}

	public function aparelho_manutencao() {
		return $this->hasMany( 'App\AparelhoManutencao', 'idequipamento' );
	}

	public function setor() {
		return $this->belongsTo( 'App\Models\Instrumentos\InstrumentoSetor', 'idsetor' );
	}

	public function cliente() {
		return $this->belongsTo( 'App\Cliente', 'idcliente' );
	}

	public function selo_instrumentos() {
		return $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )->orderBy( 'afixado_em', 'DESC');
	}

	public function lacres_instrumentos() {
		return $this->hasMany( 'App\LacreInstrumento', 'idinstrumento' )->orderBy( 'afixado_em', 'DESC' );
	}
}
