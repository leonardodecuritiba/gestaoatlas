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

	//Somente Afixar o lacre na tabela LacreInstrumento
	static public function afixar( AparelhoManutencao $aparelho, $idlacre, $now = null ) {
		$now = ( $now == null ) ? Carbon::now() : $now;
		Lacre::set_used( $idlacre );

		return LacreInstrumento::create( [
			'idlacre'          => $idlacre,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => null,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => null,
		] );

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
	    foreach ( $idslacres as $lacre ) {
		    //Nesse caso, vamos atualizar o retirado_em
		    $Data = self::where( 'idlacre', $lacre->id )->first();
		    $Data->update( [
			    'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			    'retirado_em'      => $now,
		    ] );
	    }

	    return 1;
    }

//    static public function tirar($idslacres)
//    {
//        foreach($idslacres as $lacre){
//            //Nesse caso, vamos atualizar o retirado_em
//            $Data = self::where('idlacre', $lacre->id)->first();
//            return $Data->update(['retirado_em' => Carbon::now()->toDateTimeString()]);
//        }
//        return 1;
//    }

    public function getNomeTecnico()
    {
        return $this->lacre->getNomeTecnico();
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

	public function aparelho_manutencao_set()
    {
	    return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_manutencao', 'idaparelho_set' );
    }

	public function aparelho_manutencao_unset() {
		return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_manutencao', 'idaparelho_unset' );
	}
//    public function aparelho_manutencao()
//    {
//        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
//    }
    public function instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }
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
