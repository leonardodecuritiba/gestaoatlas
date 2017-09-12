<?php

namespace App;

use App\Helpers\DataHelper;
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

	static public function updateOrdemServico( $id, array $data ) {
		$AparelhoManutencao = self::findOrFail( $id );
		//atualiza o status da O.S.
		$AparelhoManutencao->ordem_servico->update( [
			'idsituacao_ordem_servico' => 2
		] );
		$AparelhoManutencao->update( $data );

		return $AparelhoManutencao;
	}

    static public function getRelatorioIpem($data)
    {
        $query = self::whereNotNull('idinstrumento');
        if (isset($data['idtecnico'])) {
            $OS = OrdemServico::filterSeloIpem($data);
            $query->whereIn('idordem_servico', $OS->pluck('idordem_servico'));
            if ($data['numeracao'] != "") {
                $query->whereIn('idaparelho_manutencao',
                    SeloInstrumento::whereIn('idselo',
                        Selo::numeracao($data['numeracao'])->pluck('idselo')
                    )->pluck( 'idaparelho_set' )
//                    )->pluck('idaparelho_manutencao')
                );

            }
        }
        return $query->get();
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

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    //SELOS --------

    public function has_selo_retirado()
    {
        return ($this->selo_retirado() != NULL);
    }

    public function selo_retirado()
    {
	    RETURN 'AparelhoManutencao@selo_retirado ***teste***';

	    return $this->selo_instrumentos_unset->selo_retirado();
        return $this->instrumento->selo_retirado();
    }

    public function numeracao_selo_afixado()
    {
        return $this->instrumento->numeracao_selo_afixado();
    }

    public function numeracao_selo_retirado()
    {
        return $this->instrumento->numeracao_selo_retirado();
    }

	public function numeracao_selo_instrumento_retirado() {
		return $this->selo_instrumentos;
	}

    public function selo_afixado()
    {
	    RETURN 'AparelhoManutencao@selo_afixado ***teste***';
        return $this->instrumento->selo_afixado();
    }

    //LACRES --------

    public function has_lacres_retirados()
    {
        return ($this->lacres_retirados() != NULL);
    }

    public function lacres_retirados()
    {
	    RETURN 'AparelhoManutencao@lacres_retirados ***teste***';
        return $this->instrumento->lacres_retirados();
    }

    public function numeracao_lacres_afixados()
    {
        return $this->instrumento->numeracao_lacres_afixados();
    }

    public function numeracao_lacres_retirados()
    {
        return $this->instrumento->numeracao_lacres_retirados();
    }

    public function lacres_afixados()
    {
        return $this->instrumento->lacres_afixados();
    }

    //------------------------------------------------
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

	public function selo_instrumento_set()
    {
	    return $this->hasMany( 'App\SeloInstrumento', 'idaparelho_set', 'idaparelho_manutencao' );
    }

	public function lacres_instrumento_set()
    {
	    return $this->hasMany( 'App\LacreInstrumento', 'idaparelho_set', 'idaparelho_manutencao' );
    }

	public function selo_instrumento_unset() {
		return $this->hasMany( 'App\SeloInstrumento', 'idaparelho_unset', 'idaparelho_manutencao' );
	}

	public function lacres_instrumento_unset() {
		return $this->hasMany( 'App\LacreInstrumento', 'idaparelho_unset', 'idaparelho_manutencao' );
	}

    public function valor_pecas_utilizadas()
    {
        return $this->hasMany('App\PecasUtilizadas', 'idaparelho_manutencao');
    }

    public function has_instrumento()
    {
        return ($this->attributes['idinstrumento'] != NULL);
    }

    public function has_equipamento()
    {
        return ($this->attributes['idequipamento'] != NULL);
    }

    // ************************** HASMANY **********************************

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
}
