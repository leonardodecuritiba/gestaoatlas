<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'retirado_em',
        'external'
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
			self::create( [
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
	static public function retirarNovo( AparelhoManutencao $aparelho, $lacre, $now = null ) {

		$now = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;

		$lacre = Lacre::create([ // se não existir, inserir e retornar o novo id
			'idtecnico'         => Auth::user()->getIdTecnico(),
			'numeracao_externa' => $lacre,
			'externo'           => 1,
			'used'              => 1,
		]);

		return LacreInstrumento::create( [
			'idlacre'          => $lacre->idlacre,
			'idaparelho_set'   => $aparelho->idaparelho_manutencao,
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'idinstrumento'    => $aparelho->idinstrumento,
			'afixado_em'       => $now,
			'retirado_em'      => $now,
			'external'         => true,
		] );

	}

	static public function retirar( AparelhoManutencao $aparelho, $idlacre_instrumento, $now = null )
	{
		$now  = ( $now == null ) ? Carbon::now()->toDateTimeString() : $now;
		$Data = self::find( $idlacre_instrumento);
		$Data->update( [
			'idaparelho_unset' => $aparelho->idaparelho_manutencao,
			'retirado_em'      => $now,
			'external'         => $Data->lacre->isExterno(),
		] );
		return $Data;
	}

	static public function retirarHidden(AparelhoManutencao $aparelho_manutencao, $idslacres, $now = NULL)
	{
		$lacres_retirados = json_decode($idslacres);
		//Nesse caso, o SeloInstrumento já existe, vamos atualizar o retirado_em
		if(count($lacres_retirados)  > 1){
			foreach($lacres_retirados as $key => $id){
				self::retirar( $aparelho_manutencao, $id, $now );
			}
		} else {
			$id = $lacres_retirados;
			self::retirar( $aparelho_manutencao, $id, $now );
		}
	}

    public function getNomeTecnico()
    {
        return $this->lacre->getNomeTecnico();
    }

    // ******************** RELASHIONSHIP ******************************
	// ********************** BELONGS *******************************

    public function lacre()
    {
	    return $this->belongsTo(Lacre::class, 'idlacre');
    }

    public function lacres()
    {
        return $this->belongsTo(Lacre::class, 'idlacre');
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
