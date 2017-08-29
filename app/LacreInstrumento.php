<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LacreInstrumento extends Model
{
    use SeloLacreInstrumento;
    public $timestamps = true;
    protected $table = 'lacre_instrumentos';
    protected $primaryKey = 'idlacre_instrumento';
    protected $fillable = [
	    'idaparelho_set',
	    'idaparelho_unset',
        'idinstrumento',
        'idlacre',
        'afixado_em',
        'retirado_em'
    ];



    // ******************** FUNCTIONS ******************************
	//Extornar lacre
	public function extorna() {
		$this->lacre->extorna();

		return $this->delete();
	}

	//Rafixar lacre
	public function reafixa() {
		return $this->update( [
			'idaparelho_unset' => null,
			'retirado_em'      => null,
		] );
	}

	//Somente Afixar o lacre na tabela LacreInstrumento
	static public function afixar( AparelhoManutencao $aparelho, array $idslacre, $now = null ) {
		$now = ( $now == null ) ? Carbon::now() : $now;
		foreach ( $idslacre as $idlacre ) {
			Lacre::set_used( $idlacre );
			LacreInstrumento::create( [
				'idlacre'          => $idlacre,
				'idaparelho_set'   => $aparelho->idaparelho_manutencao,
				'idaparelho_unset' => null,
				'idinstrumento'    => $aparelho->idinstrumento,
				'afixado_em'       => $now,
				'retirado_em'      => null,
			] );
		}

		return true;
	}

	//Afixar e Retirar o lacre, neste caso quando é um lacre externo
	static public function retirarNovo( AparelhoManutencao $aparelho, $idlacre, $now = null ) {
		$now = ( $now == null ) ? Carbon::now() : $now;

		return LacreInstrumento::create( [
			'idlacre'          => $idlacre,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => $now,
		] );
	}

	static public function retirar( AparelhoManutencao $aparelho, $idslacres, $now = null )
    {
	    $now = ( $now == null ) ? Carbon::now() : $now;
	    foreach ( $idslacres as $idlacre ) {
		    //Nesse caso, vamos atualizar o retirado_em
		    $Data = self::where( 'idlacre', $idlacre )->first();
		    $Data->update( [
			    'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			    'retirado_em'      => $now,
		    ] );
	    }

	    return 1;
    }


    public function getNomeTecnico()
    {
        return $this->lacre->getNomeTecnico();
    }

    // ******************** RELASHIONSHIP ******************************
	// ********************** BELONGS *******************************

    public function lacre()
    {
        return $this->belongsTo('App\Lacre', 'idlacre');
    }

    public function lacres()
    {
        return $this->belongsTo('App\Lacre', 'idlacre');
    }
    // ************************** HASMANY **********************************
}
