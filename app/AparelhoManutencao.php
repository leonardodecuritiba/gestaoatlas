<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AparelhoManutencao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'aparelho_manutencaos';
    protected $primaryKey = 'idaparelho_manutencao';
    protected $fillable = [
        'idordem_servico',
        'idinstrumento',
        'idequipamento',
        'defeito',
        'solucao',
        'numero_chamado',
    ];

    // ******************** FUNCTIONS ******************************

	public function updateSeloLacreInstrumento(array $data)
	{
		//UPDATE DOS LACRES E SELOS
		//caso não tenha lacre rompido, só atualizar defeito/manutenção

//	    dd(($request->has('selo_retirado_hidden') && $request->get('selo_retirado_hidden') != NULL));
		if (isset($data['lacre_rompido'])) {
			$now = Carbon::now()->toDateTimeString();
			//Na primeira vez que o técnico for dar manutenção no instrumento, deverá marcar SELO OUTRO e LACRE OUTRO



			/*** AFIXAÇAO DO SELO ***/
			//Afixar o Selo convencional na tabela SeloInstrumento
			SeloInstrumento::afixar( $this, $data['selo_afixado'], $now );

			/*** RETIRADA DO SELO ***/
			//Nesse caso quer dizer que o selo está sendo editado pela segunda vez
			if (isset($data['selo_retirado_hidden']) && $data['selo_retirado_hidden'] != NULL)
			{
				SeloInstrumento::retirarHidden($this, $data['selo_retirado_hidden'], $now);
			}

			//Nesse caso o selo é externo ou PRIMEIRA vez
			if (isset($data['selo_outro']))
			{
				//Nesse caso, criar um selo novo na tabela selos e atribuí-lo ao técnico em questão
//		        if (Selo::selo_exists($selo_retirado)) { // Testar para saber se já existe esse selo na base, por segurnaça
//			        dd('ERRO: SELO JÁ EXISTE');
//		        }
//				$selo = Selo::create([ // se não existir, inserir e retornar o novo id
//					'idtecnico'         => $this->tecnico->idtecnico,
//					'numeracao_externa' => $data['selo_retirado'],
//					'externo'           => 1,
//					'used'              => 1,
//				]);
				//Afixar/Retirar o selo na tabela SeloInstrumento
				if(isset($data['selo_retirado'])) SeloInstrumento::retirarNovo( $this, $data['selo_retirado'], $now );

			}


			//			dd($data);
			/*** AFIXAÇAO DOS LACRES ***/
			//Afixar os lacres na tabela LacreInstrumento
			LacreInstrumento::afixar( $this, $data['lacre_afixado'], $now );

			/*** RETIRADA DOS LACRES ***/
			//Nesse caso quer dizer que os lacres está sendo editado pela segunda vez
			if (isset($data['lacres_retirado_hidden']) && $data['lacres_retirado_hidden'] != NULL)
			{
				LacreInstrumento::retirarHidden($this, $data['lacres_retirado_hidden'], $now);
			}

			//Nesse caso os lacres são externos ou PRIMEIRA vez
			if (isset($data['lacre_outro'])) {
				$lacre_retirado_livre =  trim($data['lacre_retirado_livre']);
				if(($lacre_retirado_livre != NULL) && ($lacre_retirado_livre != '')){
					$lacres_retirado = explode(';',$lacre_retirado_livre);

					//Nesse caso, criar um lacre novo na tabela lacress e atribuí-lo ao técnico em questão
					//Afixar/Retirar o lacre na tabela LacreInstrumento
					foreach ($lacres_retirado as $lacre) {
						LacreInstrumento::retirarNovo( $this, $lacre, $now );
					}
				}
			}

		}
		return;
	}

    static public function getRelatorioIpem($data)
    {
        $query = self::whereNotNull('idinstrumento');
        if (isset($data['idtecnico'])) {
	        if ($data['idtecnico'] != "") {
	            $OS = OrdemServico::filterSeloIpem($data);
	            $query->whereIn('idordem_servico', $OS->pluck('idordem_servico'));
	        }
        }
	    if (isset($data['numeracao'])) {
		    if ($data['numeracao'] != "") {
			    $query->whereIn('idaparelho_manutencao',
				    SeloInstrumento::whereIn('idselo',
					    Selo::numeracao($data['numeracao'])->pluck('idselo')
				    )->pluck( 'idaparelho_set' )
	//                    )->pluck('idaparelho_manutencao')
			    );

		    }
	    }
        return $query->get()->map(function($r){
	        $Ordem_servico = $r->ordem_servico;
	        $Cliente = $Ordem_servico->cliente->getType();
	        $selo = $r->instrumento->numeracao_selo_afixado();
        	$r->ordem_servico   = $Ordem_servico;
        	$r->colaborador     = $Ordem_servico->colaborador;
        	$r->cliente         = $Cliente;

	        $r->selo_numeracao  = $selo['text'];
	        $r->selo_declared   = $selo['declared'];
	        $r->idselo          = $selo['id'];
	        return $r;
        });
    }

    static public function check_instrumento_duplo($idordem_servico, $idinstrumento)
    {
        return parent::where('idordem_servico', $idordem_servico)
            ->where('idinstrumento', $idinstrumento)->count();
    }

    static public function check_equipamento_duplo($idordem_servico, $idequipamento)
    {
        return parent::where('idordem_servico', $idordem_servico)
            ->where('idequipamento', $idequipamento)->count();
    }

    public function remover()
    {

	    //Remover os lacres que foram afixados (nessa O.S.)
	    foreach ( $this->lacres_instrumento_set as $lacres_instrumento ) {
		    $lacres_instrumento->extorna();
	    }

	    //Reaver os lacres antigos que foram desafixados ( nessa O.S.)
	    foreach ( $this->lacres_instrumento_unset as $lacres_instrumento ) {
		    $lacres_instrumento->reafixa();
	    }

	    //Remover o selo que foi afixado (nessa O.S.)
	    foreach ( $this->selo_instrumento_set as $selo_instrumento ) {
		    $selo_instrumento->extorna();
	    }

	    //Reaver o selo antigo que foi desafixado ( nessa O.S.)
	    foreach ( $this->selo_instrumento_unset as $selo_instrumento ) {
		    $selo_instrumento->reafixa();
	    }

        //remover serviços
        $this->remove_servico_prestados();
        //remover peças
        $this->remove_pecas_utilizadas();
        //remover kits
        $this->remove_kits_utilizados();

        //remover o aparelhoManutencao
        $this->forceDelete();
        return;

    }


    // ******************** INSUMOS *******************************
    // ************************************************************

    public function remove_servico_prestados()
    {
        foreach ($this->servico_prestados as $servico_prestado) {
            $servico_prestado->forceDelete();
        }
    }

    // ******************** PEÇAS ******************************
    // ************************************************************

    public function remove_pecas_utilizadas()
    {
        foreach ($this->pecas_utilizadas as $pecas_utilizada) {
            $pecas_utilizada->forceDelete();
        }
    }

    public function remove_kits_utilizados()
    {
        foreach ($this->kits_utilizados as $kits_utilizado) {
            $kits_utilizado->forceDelete();
        }
    }

    public function hasInsumoUtilizadoId($id, $tipo)
    {
        switch ($tipo) {
            case 'servicos':
                return $this->servico_prestados()->where('idservico', $id)->first();
            case 'pecas':
                return $this->pecas_utilizadas()->where('idpeca', $id)->first();
            case 'kitss':
                return $this->kits_utilizados()->where('idkit', $id)->first();
        }

    }

    public function servico_prestados()
    {
        return $this->hasMany('App\ServicoPrestado', 'idaparelho_manutencao');
    }

    public function pecas_utilizadas()
    {
        return $this->hasMany('App\PecasUtilizadas', 'idaparelho_manutencao');
    }

    public function kits_utilizados()
    {
        return $this->hasMany('App\KitsUtilizados', 'idaparelho_manutencao');
    }

    public function has_pecas_utilizadas()
    {
        return ($this->pecas_utilizadas()->count() > 0);
    }

    // ******************** SERVIÇOS *********************************
    // ************************************************************

    public function getTotalPecasReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalPecas() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalPecas()
    {
        return $this->pecas_utilizadas->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    public function getTotalDescontoPecasReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalDescontoPecas() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalDescontoPecas()
    {
        return $this->pecas_utilizadas->sum('desconto');
    }

    public function has_servico_prestados()
    {
        return ($this->servico_prestados()->count() > 0);
    }

    public function getTotalServicosReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalServicos() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalServicos()
    {
        return $this->servico_prestados->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    // ******************** KITS **********************************
    // ************************************************************

    public function getTotalDescontoServicosReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalDescontoServicos() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalDescontoServicos()
    {
        return $this->servico_prestados->sum('desconto');
    }

    public function has_kits_utilizados()
    {
        return ($this->kits_utilizados()->count() > 0);
    }

    public function getTotalKitsReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalKits() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalKits()
    {
        return $this->kits_utilizados->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    public function getTotalDescontoKitsReal($total = NULL)
    {
        $total = ($total == NULL) ? $this->getTotalDescontoKits() : $total;
        return DataHelper::getFloat2RealMoeda($total);
    }

    public function getTotalDescontoKits()
    {
        return $this->kits_utilizados->sum('desconto');
    }

    // ******************** **** **********************************
    // ************************************************************

    public function get_total()
    {
        $total = 0;
        $total += $this->getTotalPecas();
        $total += $this->getTotalServicos();
        $total += $this->getTotalKits();
        return $total;
    }

    public function get_total_desconto()
    {
        $total = 0;
        $total += $this->getTotalDescontoPecas();
        $total += $this->getTotalDescontoServicos();
        $total += $this->getTotalDescontoKits();
        return $total;
    }

	public function valor_pecas_utilizadas()
	{
		return $this->hasMany('App\PecasUtilizadas', 'idaparelho_manutencao');
	}

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    //SELOS --------

    public function has_selo_afixado()
    {
	    return $this->selo_instrumento_set->count()>0;
    }

    public function has_selo_retirado()
    {
	    return $this->selo_instrumento_unset->count()>0;
    }

    public function numeracao_selo_afixado()
    {
        return $this->instrumento->numeracao_selo_afixado($this->getAttribute('idaparelho_manutencao'));
    }

    public function numeracao_selo_retirado()
    {
//    	return $this->instrumento;
        return $this->instrumento->numeracao_selo_retirado($this->getAttribute('idaparelho_manutencao'));
    }

	public function numeracao_selo_instrumento_retirado()
	{
		return $this->selo_instrumentos;
	}


	public function selo_instrumento_set()
	{
		return $this->hasMany( SeloInstrumento::class, 'idaparelho_set', 'idaparelho_manutencao' );
	}

	public function selo_instrumento_unset() {
		return $this->hasMany( SeloInstrumento::class, 'idaparelho_unset', 'idaparelho_manutencao' );
	}






	//LACRES --------

    public function has_lacres_retirados()
    {
        return $this->lacres_instrumento_unset->count() > 0;
    }

    public function has_lacres_afixados()
    {
        return $this->lacres_instrumento_set->count() > 0;
    }

    public function numeracao_lacres_afixados()
    {
        return $this->instrumento->numeracao_lacres_afixados($this->getAttribute('idaparelho_manutencao'));
    }

    public function numeracao_lacres_retirados()
    {
        return $this->instrumento->numeracao_lacres_retirados($this->getAttribute('idaparelho_manutencao'));
    }

    public function lacres_afixados()
    {
        return $this->instrumento->lacres_afixados();
    }

	public function lacres_instrumento_set()
	{
		return $this->hasMany( LacreInstrumento::class, 'idaparelho_set', 'idaparelho_manutencao' );
	}

	public function lacres_instrumento_unset()
	{
		return $this->hasMany( LacreInstrumento::class, 'idaparelho_unset', 'idaparelho_manutencao' );
	}

    //------------------------------------------------

    // ************************** HASMANY **********************************


	public function has_instrumento()
	{
		return ($this->attributes['idinstrumento'] != NULL);
	}

	public function has_equipamento()
	{
		return ($this->attributes['idequipamento'] != NULL);
	}

    public function ordem_servico()
    {
        return $this->belongsTo('App\OrdemServico', 'idordem_servico');
    }

    public function instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }

    public function equipamento()
    {
        return $this->belongsTo('App\Equipamento', 'idequipamento');
    }




//
//	public function selo_instrumentos()
//	{
//		return $this->hasMany('App\SeloInstrumento', 'idaparelho_manutencao');
//	}
//
//	public function lacre_instrumentos()
//	{
//		return $this->hasMany('App\LacreInstrumento', 'idaparelho_manutencao');
//	}

	/*

		public function selo_afixado()
		{
			RETURN 'AparelhoManutencao@selo_afixado ***teste***';
			return $this->instrumento->selo_afixado();
		}

		public function lacres_retirados()
		{
			RETURN 'AparelhoManutencao@lacres_retirados ***teste***';
			return $this->instrumento->lacres_retirados();
		}
		*/
}
