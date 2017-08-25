<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeloInstrumento extends Model
{
    use SeloLacreInstrumento;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'selo_instrumentos';
    protected $primaryKey = 'idselo_instrumento';
    protected $fillable = [
	    'idaparelho_set',
	    'idaparelho_unset',
        'idinstrumento',
        'idselo',
        'afixado_em',
        'retirado_em'
    ];



    // ******************** FUNCTIONS ******************************

	//Somente Afixar o selo na tabela SeloInstrumento
	static public function afixar( AparelhoManutencao $aparelho, $idselo, $now = null ) {
		$now = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;
		Selo::set_used( $idselo );

		return SeloInstrumento::create( [
			'idselo'           => $idselo,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => null,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => null,
		] );
	}

	//Afixar e Retirar o selo, neste caso quando é um selo externo
	static public function retirarNovo( AparelhoManutencao $aparelho, $idselo, $now = null ) {
		$now = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;

		return LacreInstrumento::create( [
			'idlacre'          => $idselo,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => $now,
		] );
	}

	//Nesse caso, criar o SeloInstrumento já existe, vamos atualizar o retirado_em
	static public function retirar( AparelhoManutencao $aparelho, $numeracao, $now = null ) {
		$now  = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;
		$Selo = Selo::where( 'numeracao', $numeracao )->first();
		$Data = self::where( 'idselo', $Selo->idselo )->first();

		return $Data->update( [
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'retirado_em'      => $now,
		] );
    }

    public function getNomeTecnico()
    {
        return $this->selo->getNomeTecnico();
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

//    public function aparelho_manutencao()
//    {
//        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
//    }
	public function aparelho_manutencao_set()
    {
	    return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_manutencao', 'idaparelho_set' );
    }

	public function aparelho_manutencao_unset() {
		return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_manutencao', 'idaparelho_unset' );
    }
    public function instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }
    public function selo()
    {
        return $this->belongsTo('App\Selo', 'idselo');
    }
    // ************************** HASMANY **********************************
}
