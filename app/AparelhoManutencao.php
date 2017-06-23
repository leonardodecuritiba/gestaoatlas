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
    ];

    // ******************** FUNCTIONS ******************************
    static public function getRelatorioIpem($data)
    {
        $query = self::whereNotNull('idinstrumento');
        if (isset($data['idtecnico'])) {
            $OS = OrdemServico::filterByIdTecnicoDate($data);
            $query->whereIn('idordem_servico', $OS->pluck('idordem_servico'));
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
        foreach ($this->lacre_instrumentos as $lacre_instrumento) {
            //atualizar used
//            echo "Removendo lacre: " . $lacre_instrumento->lacre->idlacre . "\n";
            $lacre_instrumento->lacre->extorna();
            //remover lacre_instrumento
//            echo "Removendo lacre_instrumento: " . $lacre_instrumento->idlacre_instrumento . "\n";
            $lacre_instrumento->delete();
        }
        //Remover os selos
        foreach ($this->selo_instrumentos as $selo_instrumento) {
            //atualizar used
//            echo "Removendo selo: " . $selo_instrumento->selo->idselo . "\n";
            $selo_instrumento->selo->extorna();
            //remover selo_instrumento
//            echo "Removendo selo_instrumento: " . $selo_instrumento->idselo_instrumento . "\n";
            $selo_instrumento->delete();
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

    public function getTotalPecasReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalPecas());
    }

    public function getTotalPecas()
    {
        return $this->pecas_utilizadas->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    public function getTotalDescontoPecasReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalDescontoPecas());
    }

    public function getTotalDescontoPecas()
    {
        return $this->pecas_utilizadas->sum('desconto');
    }

    public function has_servico_prestados()
    {
        return ($this->servico_prestados()->count() > 0);
    }

    public function getTotalServicosReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalServicos());
    }

    public function getTotalServicos()
    {
        return $this->servico_prestados->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    // ******************** KITS **********************************
    // ************************************************************

    public function getTotalDescontoServicosReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalDescontoServicos());
    }

    public function getTotalDescontoServicos()
    {
        return $this->servico_prestados->sum('desconto');
    }

    public function has_kits_utilizados()
    {
        return ($this->kits_utilizados()->count() > 0);
    }

    public function getTotalKitsReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalKits());
    }

    public function getTotalKits()
    {
        return $this->kits_utilizados->sum(function ($p) {
            return ($p->valor * $p->quantidade) - $p->desconto;
        });
    }

    public function getTotalDescontoKitsReal()
    {
        return DataHelper::getFloat2RealMoeda($this->getTotalDescontoKits());
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

    public function numeracao_selo_afixado()
    {
        $selo = $this->selo_afixado();
        return ($selo != NULL) ? $selo->getFormatedSeloDV() : '-';
    }

    public function selo_afixado()
    {
        return $this->instrumento->selo_afixado();
    }

    public function numeracao_selo_retirado()
    {
        $selo = $this->selo_retirado();
        return ($selo != NULL) ? $selo->getFormatedSeloDV() : '-';
    }

    public function selo_retirado()
    {
        return $this->instrumento->selo_retirado();
    }

    public function has_selo_retirado()
    {
        $lacresInstrumento = $this->selo_retirado();
        return ($lacresInstrumento != NULL);
    }

    public function has_lacres_retirados()
    {
        $lacresInstrumento = $this->lacres_retirados();
        return ($lacresInstrumento != NULL);
    }

    public function lacres_retirados()
    {
        return $this->instrumento->lacres_retirados();
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

    public function lacres_afixados()
    {
        return $this->instrumento->lacres_afixados();
    }

    public function selo_instrumentos()
    {
        return $this->hasMany('App\SeloInstrumento', 'idaparelho_manutencao');
    }

    public function lacre_instrumentos()
    {
        return $this->hasMany('App\LacreInstrumento', 'idaparelho_manutencao');
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
