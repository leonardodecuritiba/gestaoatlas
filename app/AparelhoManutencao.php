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

    // ******************** SERVIÇOS ******************************
    // ************************************************************

    public function remove_servico_prestados()
    {
        foreach ($this->servico_prestados as $servico_prestado) {
            $servico_prestado->forceDelete();
        }
    }

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

    public function has_servico_prestados()
    {
        return ($this->servico_prestados()->count() > 0);
    }

    public function servico_prestados()
    {
        return $this->hasMany('App\ServicoPrestado', 'idaparelho_manutencao');
    }


    // ******************** PEÇAS *********************************
    // ************************************************************

    public function getTotalServicosReal()
    {
        return DataHelper::getFloat2Real($this->getTotalServicos());
    }

    public function getTotalServicos()
    {
        return $this->servico_prestados->sum('valor_float');
    }

    public function has_pecas_utilizadas()
    {
        return ($this->pecas_utilizadas()->count() > 0);
    }

    public function pecas_utilizadas()
    {
        return $this->hasMany('App\PecasUtilizadas', 'idaparelho_manutencao');
    }

    public function getTotalPecasReal()
    {
        return DataHelper::getFloat2Real($this->getTotalPecas());
    }


    // ******************** KITS **********************************
    // ************************************************************

    public function getTotalPecas()
    {
        return $this->pecas_utilizadas->sum('valor_float');
    }

    public function has_kits_utilizados()
    {
        return ($this->kits_utilizados()->count() > 0);
    }

    public function kits_utilizados()
    {
        return $this->hasMany('App\KitsUtilizados', 'idaparelho_manutencao');
    }

    public function getTotalKitsReal()
    {
        return DataHelper::getFloat2Real($this->getTotalKits());
    }

    public function getTotalKits()
    {
        return $this->kits_utilizados->sum('valor_float');
    }

    // ******************** **** **********************************
    // ************************************************************

    public function get_total()
    {
        $total = 0;
        $total += $this->pecas_utilizadas->sum('valor_float');
        $total += $this->servico_prestados->sum('valor_float');
        $total += $this->kits_utilizados->sum('valor_float');
        return $total;
    }



    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
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

    public function has_equipamentos()
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
