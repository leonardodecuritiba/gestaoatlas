<?php

namespace App;

use App\Helpers\DataHelper;
use App\Models\Inputs\Instrument;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        'retirado_em',
	    'external'
    ];



    // ******************** FUNCTIONS ******************************
	//Extornar selo
	public function extorna() {
		$this->selo->extorna();

		return $this->delete();
	}

	//Rafixar selo
	public function reafixa() {
		return $this->update( [
			'idaparelho_unset' => null,
			'retirado_em'      => null,
		] );
	}

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
			'external'         => false,
		] );
	}

	//Afixar e Retirar o selo, neste caso quando é um selo externo
	static public function retirarNovo( AparelhoManutencao $aparelho, $selo, $now = null ) {
		$now = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;

		$selo = Selo::create([ // se não existir, inserir e retornar o novo id
			'idtecnico'         => Auth::user()->getIdTecnico(),
			'numeracao_externa' => $selo,
			'externo'           => 1,
			'used'              => 1,
		]);

		return SeloInstrumento::create( [
			'idselo'           => $selo->idselo,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => $now,
			'external'         => true,
		] );
	}

	//Nesse caso, criar o SeloInstrumento já existe, vamos atualizar o retirado_em
	static public function retirar( AparelhoManutencao $aparelho, $idselo_instrumento, $now = null )
	{
		$now  = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;
		$Data = self::find( $idselo_instrumento);
		$Data->update( [
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'retirado_em'      => $now,
			'external'         => $Data->selo->isExterno(),
		] );
		return $Data;
    }

	static public function retirarHidden(AparelhoManutencao $aparelho_manutencao, $idselos, $now = NULL)
	{
		$selos_retirados = json_decode($idselos);
		//Nesse caso, o SeloInstrumento já existe, vamos atualizar o retirado_em
		if(count($selos_retirados)  > 1){
			foreach($selos_retirados as $key => $id){
				self::retirar( $aparelho_manutencao, $id, $now );
			}
		} else {
			$id = $selos_retirados;
			self::retirar( $aparelho_manutencao, $id, $now );
		}
	}

    public function getNomeTecnico()
    {
        return $this->selo->getNomeTecnico();
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function selo()
    {
        return $this->belongsTo(Selo::class, 'idselo');
    }

    public function instrumento()
    {
        return $this->belongsTo(Instrumento::class, 'idinstrumento','idinstrumento');
    }

    public function aparelho_set()
    {
        return $this->belongsTo(AparelhoManutencao::class, 'idaparelho_set','idaparelho_manutencao');
    }
    public function aparelho_unset()
    {
        return $this->belongsTo(AparelhoManutencao::class, 'idaparelho_unset','idaparelho_manutencao');
    }
    // ************************** HASMANY **********************************
}
