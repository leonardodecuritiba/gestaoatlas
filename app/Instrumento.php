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

	public function selo_afixado() {
		$SeloInstrumento = $this->selo_instrumentos_afixado->first();

		return ( $SeloInstrumento == null ) ? $SeloInstrumento : $SeloInstrumento->selo;

//		if ( $SeloInstrumento ) {
////            $SeloInstrumento = $this->selo_instrumentos()->whereNull('retirado_em')->first();
//			$SeloInstrumento = $this->selo_instrumentos_afixado();
//
//			return ( $SeloInstrumento != null ) ? $SeloInstrumento->selo : $SeloInstrumento;
//		}
//
//		return null;
	}

	public function selo_retirado() {
		if ( $this->has_selo_instrumentos_retirado() ) {
//			$SeloInstrumento = $this->selo_instrumentos()->whereNotNull( 'retirado_em' )->first();
			$SeloInstrumento = $this->selo_instrumentos_retirado()->first();

			return ( $SeloInstrumento != null ) ? $SeloInstrumento->selo : $SeloInstrumento;
		}

		return null;
	}

    public function numeracao_selo_afixado()
    {
        $selo = $this->selo_afixado();
        return ($selo != NULL) ? $selo->getFormatedSeloDV() : '-';
    }

	public function numeracao_selo_retirado() {
		$selo = $this->selo_retirado();
		return ( $selo != null ) ? $selo->getFormatedSeloDV() : '-';
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
	public function lacres_afixados_list() {
		$lacres = $this->lacres_afixados();

		return ( $lacres == null ) ? $lacres : $lacres->map( function ( $s ) {
			return [
				'id'   => $s->lacre->idlacre,
				'text' => $s->lacre->numeracao
			];
		} );
	}

	public function lacres_afixados() {
		if ( $this->has_lacres_instrumentos_afixados() ) {
			$LacresInstrumento = $this->lacres_instrumentos_afixados()->whereNull( 'retirado_em' )->get();

			return $LacresInstrumento;
		}

		return null;
	}

	public function lacres_retirados() {
		if ( $this->has_lacres_instrumentos_retirados() ) {
			$LacresInstrumento = $this->lacres_instrumentos_retirados()->whereNotNull( 'retirado_em' )->get();

			return $LacresInstrumento;
		}

		return null;
	}

    public function numeracao_lacres_afixados()
    {
        $lacresInstrumento = $this->lacres_afixados();
        $numeracao = NULL;
        if ($lacresInstrumento != NULL) {
            foreach ($lacresInstrumento as $li) {
                $lacre = $li->lacre;
                $numeracao[] = ($lacre->numeracao != NULL) ? $lacre->numeracao : $lacre->numeracao_externa;
            }
        }
        return ($numeracao != NULL) ? implode('; ', $numeracao) : '-';
    }

    public function numeracao_lacres_retirados()
    {
        $lacresInstrumento = $this->lacres_retirados();
        $numeracao = NULL;
        if ($lacresInstrumento != NULL) {
            foreach ($lacresInstrumento as $li) {
                $lacre = $li->lacre;
                $numeracao[] = ($lacre->numeracao != NULL) ? $lacre->numeracao : $lacre->numeracao_externa;
            }
        }
        return ($numeracao != NULL) ? implode('; ', $numeracao) : '-';
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

	public function setor() {
		return $this->belongsTo( 'App\Models\Instrumentos\InstrumentoSetor', 'idsetor' );
	}

	public function cliente() {
		return $this->belongsTo( 'App\Cliente', 'idcliente' );
	}

	public function selo_instrumentos() {
		return $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )->orderBy( 'retirado_em' );
	}
	
	public function lacres_instrumentos() {
		return $this->hasMany( 'App\LacreInstrumento', 'idinstrumento' )->orderBy( 'retirado_em' );
	}

	public function selo_instrumentos_retirado() {
		return $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )->whereNotNull( 'idaparelho_unset' )->orderBy( 'retirado_em' );
	}

	public function selo_instrumentos_afixado() {
		return $this->hasMany( 'App\SeloInstrumento', 'idinstrumento' )->whereNull( 'idaparelho_unset' )->orderBy( 'retirado_em' );
	}

	public function lacres_instrumentos_retirados() {
		return $this->hasMany( 'App\LacreInstrumento', 'idinstrumento', 'idinstrumento' )->whereNotNull( 'idaparelho_unset' )->orderBy( 'retirado_em' );
	}

	public function lacres_instrumentos_afixados() {
		return $this->hasMany( 'App\LacreInstrumento', 'idinstrumento', 'idinstrumento' )->whereNull( 'idaparelho_unset' )->orderBy( 'retirado_em' );
	}

	public function aparelho_manutencao() {
		return $this->hasMany( 'App\AparelhoManutencao', 'idequipamento' );
	}
}
